# JF Landing Page Template

A WordPress page template that displays a landing page or form built in **Nexus** full screen on a client's WordPress site. It is tuned for the best possible PageSpeed score.

You build the page in Nexus, paste its iframe embed code into a WordPress page, and pick this template. The template takes care of the rest.

---

## What it does

- **Renders only the Nexus page.** No theme header, footer, menus, sidebars or plugin scripts are loaded. The page is a light HTML wrapper with the Nexus iframe covering the whole screen.
- **Keeps SEO from Yoast.** The template outputs Yoast's head tags (title, meta description, canonical, Open Graph) and respects the page's *noindex* / *nofollow* settings.
- **Shows a loader while Nexus loads.** A small spinner covers the screen until the Nexus page has loaded, so visitors don't see the page jump around while it builds itself.
- **Fixes the embed code automatically.** You don't need to edit the iframe Nexus gives you. See [What the template adjusts](#what-the-template-adjusts).

---

## How to use it

1. In Nexus, copy the landing page's or form's **iframe embed code**. It looks like this:

   ```html
   <iframe src="https://app.jumpfactor.co/v2/preview/XXXXXXXX" title="Page name" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allow="autoplay; encrypted-media; gyroscope"></iframe>
   ```

2. In WordPress, create or edit a page.
3. Paste the iframe **exactly as it comes from Nexus**. Use a *Custom HTML* block, or the *Code editor* view (Classic editor: the *Text* tab).
4. In **Page Attributes → Template**, select **JF Landing Page**.
5. Set up Yoast as usual: SEO title, meta description, and noindex if needed.
6. Publish, then **purge the NitroPack cache** for that page if the site uses NitroPack.

That's it. Use the iframe as is. Don't add width, height, styles or wrappers to it.

> **Only the iframe is shown.** Anything else in the page content (text, images, other blocks) is removed when the page is rendered. Put all of the content inside the Nexus page.

---

## What the template adjusts

When the page is rendered, the template changes the pasted iframe as follows:

| Change | Why |
|---|---|
| Removes `loading="lazy"` | The iframe fills the screen from the start. Lazy loading only makes the browser wait before starting it. |
| Adds `nitro-exclude` | Stops NitroPack from lazy-loading or delaying the iframe. |
| Makes it fill the screen | Fixed full-screen size with no border, whatever width or height the embed says. |
| Strips everything that isn't an iframe | Keeps the output clean and safe (`wp_kses`). |

Nothing is saved back to the page. These changes only happen in the HTML sent to the browser, so the content in WordPress stays exactly as you pasted it.

---

## How the loading works

1. The page loads with the spinner showing and the Nexus iframe hidden behind it.
2. When the Nexus page finishes loading, the template waits 300 ms and then fades the iframe in.
3. If Nexus takes too long, the iframe is shown anyway:
   - **Mobile** (screen up to 767 px): after **8 seconds**
   - **Desktop**: after **3 seconds**

**Why the limits differ:**

- **On mobile**, Nexus pages move a lot while they load: images without fixed sizes, web fonts swapping in, entrance animations. Keeping the iframe hidden until it has loaded means visitors don't see those jumps, and PageSpeed doesn't count them as layout shift (CLS).
- **On desktop**, the page shows up faster. Revealing it sooner keeps the Speed Index low.

---

## Installation

1. Copy `template-jf-landing.php` into the **root of the active theme**. If the site uses a child theme, put it in the child theme.
2. In any page, **JF Landing Page** now appears under **Page Attributes → Template**.

Requirements: WordPress with page templates. Yoast SEO is recommended but optional; without it, the page has no title or meta tags.

---

## Performance notes

- The template itself adds almost nothing to the page weight. The score mostly depends on **what's inside the Nexus page**.
- What lowers the score most on mobile is heavy content in Nexus:
  - Large images. Export them as WebP or JPG at about 1200 px wide. Avoid SVG exports from Figma that have photos embedded inside.
  - Many web fonts.
  - Entrance animations on the first section.
- To compare results, run PageSpeed **at least 3 times** for both mobile and desktop and look at the average. Scores vary from one run to the next, especially TBT on desktop.

---

## Troubleshooting

| Problem | Check |
|---|---|
| Blank page / only the spinner | Make sure the page content has an `<iframe>` and that its `src` URL opens on its own. |
| Old version still showing | Purge NitroPack, Cloudflare and any other caches for the page. |
| The template doesn't show up in Page Attributes | The file must be in the root of the **active** theme or child theme. |
| `robots` meta tag appears twice in the source | Yoast already prints it; remove the manual robots block from the template. |
