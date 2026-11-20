# IndigeneTech audit remediation (19 Nov 2026)

Remediation of "indigenetech.ca — Unused CSS/JS & Broken Links Audit", revalidated against the repository and the live site on 19 Nov 2026. Branch `claude/bold-mccarthy-st4ze4`.

**Deployed to staging on 20 Nov 2026 (commit 652f7a0) and verified there; not on live.** The staging server check passes 8/8 on 29 pages, the browser interaction suite passes 31/31, and pages match live visually.

## Status summary

| Status | Audit findings | All tracked items |
|---|---|---|
| Fixed and verified | 19 | 23 |
| Fixed, awaiting validation | 0 | 0 |
| Not reproducible | 1 | 1 |
| Requires business approval | 2 | 13 |
| Blocked by technical dependency | 0 | 2 |
| Intentionally retained (required) | 3 | 4 |

## Before and after (measured on live content)

Initial page load, no scrolling, averaged over the 25 pages; every asset gzip-compressed the same way.

| Viewport | Metric | Before | After |
|---|---|---|---|
| desktop | Stylesheets per page | 41 | 25 |
| desktop | CSS per page (gzip) | 154 KB | 105 KB |
| desktop | Scripts per page | 29 | 19 |
| desktop | JavaScript per page (gzip) | 871 KB | 403 KB |
| desktop | …of which third-party | 739 KB | 283 KB |
| desktop | Fonts per page | 8 files, 519 KB | 8 files, 516 KB |
| mobile | Stylesheets per page | 41 | 25 |
| mobile | CSS per page (gzip) | 154 KB | 105 KB |
| mobile | Scripts per page | 29 | 18 |
| mobile | JavaScript per page (gzip) | 927 KB | 142 KB |
| mobile | …of which third-party | 794 KB | 22 KB |
| mobile | Fonts per page | 8 files, 522 KB | 8 files, 516 KB |

| Check | Before | After |
|---|---|---|
| Interaction suite (popup, forms, menus, FAQ, tabs, accordion, carousels, icons, fonts, mobile menu) | 22 / 31 pass | 31 / 31 pass (simulation and staging) |
| Placeholder links (href="#", javascript:) across 25 pages | 56 (2 per page, Software card, 5 FAQ) | 0 |
| Internal links that redirect | 3 links on 3 pages | 0 |
| JavaScript errors from site code (FAQ TypeError, hbspt is not defined) | Yes (home, services, blog, contact) | None |
| HTTP 4xx/5xx on the 25 pages and their assets | 0 | 0 |
| Horizontal overflow at 390 px | 0 pages | 0 pages |
| Page height with forms rendered (home, service, contact, blog; desktop and mobile) | baseline | identical (pixel diff ≤ 0.15%, autoplay sliders only) |

## Audit findings

### Broken interactions and links

#### B1. "Get started" popup dead on the 10 service pages

- **Status:** Fixed and verified
- **Revalidated:** Reproduced. Clicking "Get started" on /ai-ml-development/ does nothing (popup code only loaded on the homepage).
- **Action:** Popup code moved to a site-wide script; one close/Escape/overlay handler; popup form created on first open (branch fix/phase2-quick-fixes, merged).
- **Files:** `wp-content/themes/indigenetech-child/assets/js/itech-site.js`, `wp-content/themes/indigenetech-child/functions.php`, `wp-content/themes/indigenetech-child/assets/js/why-choose.js`
- **Evidence:** Staging: popup opens from a service page, form renders on open, Escape and close button work. Live simulation: popup opens, HubSpot form renders on open, Escape and the close button close it (interaction suite 31/31; live today fails 8 of the same checks). Local WordPress: popup markup printed once.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### B2. Zero-width spaces in the Contact page email, address and phone

- **Status:** Requires business approval
- **Revalidated:** Reproduced on live: 3 zero-width spaces remain (after the address, the email and the phone). Already removed on staging (database).
- **Action:** Database edit on live (Elementor data of the Contact page), as done on staging.
- **Files:** `Database: Contact page _elementor_data (live)`
- **Evidence:** Staging HTML has 0 zero-width characters and adds tel:+18259019111.
- **Next check:** Owner approves repeating the staging edit on live.

#### B3. Placeholder links: href="#" on all 25 pages, javascript:void(0) on the homepage

- **Status:** Fixed and verified
- **Revalidated:** Reproduced. Site-wide: the "What We Do" menu item links to "#" twice per page (desktop mega menu and mobile menu); clicking it jumps to /#. Homepage: "Software Development" card links to "#"; 5 FAQ questions use javascript:void(0).
- **Action:** "What We Do" renders as a focusable toggle with no href (desktop walker and mobile menu filter); the mega menu now also opens on keyboard focus; tapping the label in the mobile menu expands its sub-menu. Software Development card points to /enterprise-custom-software-development/. FAQ questions are buttons with aria-expanded.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`, `wp-content/themes/indigenetech-child/assets/js/itech-site.js`, `wp-content/themes/indigenetech-child/assets/css/common.css`, `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`, `wp-content/themes/indigenetech-child/assets/css/newhome.css`
- **Evidence:** Staging: 0 href="#" / javascript: links on 29 pages; mega menu opens by hover and keyboard; mobile label expands the sub-menu. Simulation: 0 href="#" and 0 javascript: links on all 25 pages and on the 5 extra templates; mega menu opens by hover and keyboard; mobile label expands the sub-menu without navigating; FAQ styling unchanged (pixel diff 0.00–0.15%, differences are autoplay sliders).
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.
- **Note:** The Software Development card target is my best match (there is no generic software page). Confirm or give the intended page.

#### B4. Internal links that redirect

- **Status:** Fixed and verified
- **Revalidated:** Reproduced, plus one the audit missed. /app-development (no slash) on the homepage and in the 'digital tools' post; the CCIB post links twice to https://indigenetech.ca/ (no www), both 301.
- **Action:** Homepage link fixed in the template. Content links are normalised on output: apex domain to www, trailing slash added to page links (files, queries, anchors and wp-* paths untouched).
- **Files:** `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`, `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: 40 internal links return 200; the CCIB and 'digital tools' posts have no apex-domain or slash-less links. Unit test of the normaliser: 16/16 cases pass (including look-alike hosts). Simulated crawl: 0 internal links redirect.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.
- **Note:** Optional later clean-up: correct the two posts in the database so the filter has nothing to do.

#### B5. Parent style.css references missing assets/images/01.png

- **Status:** Fixed and verified
- **Revalidated:** Reproduced (rule never matches, but the reference is broken).
- **Action:** Removed the background-image line; the rest of the rule is unchanged.
- **Files:** `wp-content/themes/indigenetech/style.css`
- **Evidence:** Staging: all stylesheets load; no 4xx. grep: 0 references to 01.png. Simulated crawl: no 4xx responses.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### B6. /cdn-cgi/l/email-protection 404s

- **Status:** Not reproducible
- **Revalidated:** Not reproducible as a defect: Cloudflare email obfuscation. The crawl recorded 0 responses of 400 or above on all 25 pages.
- **Action:** None.
- **Evidence:** Baseline and simulated crawls: http4xx/5xx = 0.
- **Next check:** —

#### B7. External links never checked

- **Status:** Requires business approval
- **Revalidated:** Checked 18 unique external links (the 33 AI share links on the 11 posts are prefilled ChatGPT/Perplexity/Google links and were not followed). 11 return 200. 1 broken: itenterprise.co.uk/bespoke-software-go-bespoke/ returns 404 on /blog/indigenous-owned-business/. http://www.ccib.ca/ works over https (update the link to https). canada.ca (federal sustainable development strategy) times out for automated clients: unverified. Facebook, Instagram and LinkedIn profiles could not be reached from the test environment.
- **Action:** Content edits in the database.
- **Files:** `Database: 2 blog posts`
- **Evidence:** curl with a browser user agent, following redirects.
- **Next check:** Owner decides on a replacement for the 404 link; someone opens the 3 social links and the canada.ca link by hand.

### Unused and duplicate JavaScript

#### J1. Owl Carousel JS + CSS on all 25 pages

- **Status:** Fixed and verified
- **Revalidated:** Reproduced: 43 KB JS + 3 KB CSS everywhere; no .owl-carousel markup on any page (rendered DOM included).
- **Action:** Dequeued. The parent stylesheet no longer depends on it.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: no Owl Carousel on any page. Local WordPress: not requested. Simulation: no JS errors on any page; theme script still runs (it only initialises Owl when a carousel exists).
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### J2. ElementsKit header search / off-canvas / nav-menu / Magnific Popup on all pages

- **Status:** Fixed and verified
- **Revalidated:** Reproduced: ElementsKit force-loads its header-builder assets (6 scripts, 4 stylesheets) on every page. The header is the theme's own header.php; none of these widgets exist.
- **Action:** Dequeued after ElementsKit queues them. A page that ever adds one of those widgets still gets its files through Elementor's widget dependencies.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: no ElementsKit header or Magnific assets; tabs, accordion and footer post list work. Local WordPress: not requested. Simulation: menus, footer post list, tabs and accordions all work; no JS errors.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### J3. ElementsKit tab script on 11 pages with no tab markup

- **Status:** Intentionally retained (required)
- **Revalidated:** Not reproducible on 10 of the 11 pages: every service page has 2 ElementsKit Simple Tab widgets (16 tab titles) and an ElementsKit accordion. The 11th page is the homepage, which has no tabs.
- **Action:** Kept on the 10 service pages. On the homepage, the tab, accordion, image-box and block-library assets (queued from stored Elementor content the custom template never renders) are dropped.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Simulation: service-page accordions toggle; homepage no JS errors and visually unchanged.
- **Next check:** Homepage on staging: no ekit-tab / ekit-accordion / ui-slim requests; service pages: tabs switch.
- **Note:** Status covers the service pages (required). The homepage part is verified on staging.

#### J4. why-choose.js slider/load-more code with no matching markup

- **Status:** Fixed and verified
- **Revalidated:** Reproduced: no #sliderTrack, .why-choose or .load-more-btn on any page; nothing in why-choose.css matches the homepage, the only page that loads it.
- **Action:** why-choose.css/.js now load only when the [why_choose_section] shortcode renders; the dead load-more handler and non-slider rules were removed; the slider no longer needs jQuery.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`, `wp-content/themes/indigenetech-child/assets/js/why-choose.js`, `wp-content/themes/indigenetech-child/assets/css/why-choose.css`
- **Evidence:** Staging: why-choose files not loaded on the homepage. Local WordPress: a page with the shortcode loads both files and the slider moves; the homepage loads neither.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### J5. HubSpot v2.js loaded 4 times (home/services) or 2 times (blog/contact)

- **Status:** Fixed and verified
- **Revalidated:** Reproduced: 2–4 script tags per page each execute the 575 KB embed script and each throw 'hbspt is not defined' while it loads (the browser cache absorbs the repeat downloads).
- **Action:** One loader for the whole page (branch fix). In-page forms are now created when they come within 600 px of the viewport, so the script is not downloaded for visitors who never scroll to a form. Popup form created on first open.
- **Files:** `wp-content/themes/indigenetech-child/assets/js/itech-site.js`, `wp-content/themes/indigenetech-child/functions.php`, `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`
- **Evidence:** Staging: v2.js not loaded at page load; one script tag, loaded on scroll; all forms render (3 on home/services, 1 on blog/contact), no errors. Local WordPress, original theme: 4 main-frame loads and 4 'hbspt is not defined' errors on the homepage; new theme: 1 load, 0 errors. Simulation: v2.js requested once, every form renders after scrolling (3 on home/services, 1 on blog/contact, same heights as today).
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### J6. Dead files in the child theme

- **Status:** Fixed and verified
- **Revalidated:** Confirmed: 8 JS files identical to the parent's, 2 identical CSS files, slider.js, a stray assets/js/functions.php from another theme, assets/library (1.6 MB), assets/images (480 KB) and template-parts/content-download.php (EDD not installed). None is referenced by code, CSS or page content.
- **Action:** Deleted.
- **Files:** `wp-content/themes/indigenetech-child/assets/js/*`, `wp-content/themes/indigenetech-child/assets/css/appzend-admin.css`, `wp-content/themes/indigenetech-child/assets/css/customizer.css`, `wp-content/themes/indigenetech-child/assets/library/`, `wp-content/themes/indigenetech-child/assets/images/`, `wp-content/themes/indigenetech-child/template-parts/content-download.php`
- **Evidence:** Staging: removed files return 404. grep of themes, plugins and the 25 pages' HTML: no references. Local WordPress runs with them removed (all pages 200, no PHP warnings).
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

### Unused and duplicate CSS

#### C1. Parent style.css: 574/691 rules unused (84%)

- **Status:** Intentionally retained (required)
- **Revalidated:** Confirmed (8% of bytes used on any one page).
- **Action:** Kept. It is the base theme stylesheet (reset, typography, mobile menu, states); the audit itself advised against a wholesale purge. Only the broken 01.png reference was removed (B5).
- **Evidence:** Coverage per page: 8% max.
- **Next check:** —

#### C2. common.css: 429/631 rules unused (53%), mostly utility classes

- **Status:** Intentionally retained (required)
- **Revalidated:** Confirmed. 310 of the 375 unused selectors are utility classes (spacing, sizes, colours) that editors apply through Elementor's CSS Classes field.
- **Action:** Utility classes kept for current drafts and future pages. Dead component rules removed (.advanced-col, .see-less-btn, .bolt-text-mega-menu, .header-inner, .header-wrapper, .govt-cont, .sec-head, .site-btn). Two site-wide rules moved in from newhome.css.
- **Files:** `wp-content/themes/indigenetech-child/assets/css/common.css`
- **Evidence:** Pruner: a selector is removed only if one of its classes/ids appears in none of the 25 pages' HTML, the rendered DOM after interactions (50 snapshots), theme PHP/JS, or any plugin's PHP/JS.
- **Next check:** —
- **Note:** Utility library retained by design; dead component rules removed (verified on staging).

#### C3. custom.css: 56/75 rules unused (CF7, old Elementor IDs, circle/timeline/essence)

- **Status:** Fixed and verified
- **Revalidated:** Confirmed.
- **Action:** 41 rules removed (10.3 KB → 4.4 KB). Versioned by file time instead of the WordPress version.
- **Files:** `wp-content/themes/indigenetech-child/assets/css/custom.css`, `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: pages visually match live (pixel diff 0.03-0.53% after the 26 px header offset; sliders and reCAPTCHA). Same pruner rules as C2. Pixel diff of home, service, contact and blog pages at desktop and mobile: 0.00–0.15% (autoplay sliders only).
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### C4. newhome.css: 27% unused, loads on every page

- **Status:** Fixed and verified
- **Revalidated:** Confirmed. Checked before restricting it: on service, blog and contact pages only 3 generic rules match (link outline, button hover reset). No header, mega menu, popup or footer styles live in it.
- **Action:** 76 dead selectors removed (old homepage sections). The 2 site-wide rules moved to common.css; newhome.css now loads only with the homepage template.
- **Files:** `wp-content/themes/indigenetech-child/assets/css/newhome.css`, `wp-content/themes/indigenetech-child/assets/css/common.css`, `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: newhome.css only on the homepage; other pages visually unchanged. Local WordPress: newhome.css on / only. Simulation: all pages visually unchanged.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### C5. why-choose.css: 20/23 rules unused

- **Status:** Fixed and verified
- **Revalidated:** Worse than reported: 0 rules match on the homepage, the only page that loads it.
- **Action:** See J4: trimmed to the slider rules and loaded only with the shortcode.
- **Files:** `wp-content/themes/indigenetech-child/assets/css/why-choose.css`
- **Evidence:** Staging: not loaded on any page. See J4.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### C6. Child style.css is empty but still requested

- **Status:** Fixed and verified
- **Revalidated:** Confirmed (header comment only).
- **Action:** The file is no longer requested; the theme's customizer CSS attached to that handle is still printed inline.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: child style.css not requested. Local WordPress: no style.css request; appzend-style-inline-css still printed.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### C7. Font Awesome loaded up to 5 ways

- **Status:** Fixed and verified
- **Revalidated:** Confirmed: theme FA 5.11 all.min.css, Elementor FA5 core, Header Footer Elementor's unminified FA5 core/brands/solid, and FA 7.3.1 from cdnjs on the homepage. Every icon on the site is an FA5 solid or brands icon; the homepage used FA6 syntax (fa-solid) for 10 chevrons, which is the only reason FA7 was loaded.
- **Action:** One copy: Elementor's FA5 core + solid + brands (registered from Elementor's bundled files if Elementor's own setting doesn't register them). Homepage chevrons switched to fas.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`, `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`
- **Evidence:** Staging: every icon renders with Font Awesome 5 on home, service and blog pages. Simulation: every icon renders (19 on home, 23 on a service page, 9 on a blog post), FA5 fonts loaded. Local WordPress: 3 FA files instead of 5.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### C8. Elementor eicons twice; widget-icon-list and widget-social-icons twice

- **Status:** Fixed and verified
- **Revalidated:** Confirmed: Header Footer Elementor enqueues its own copies.
- **Action:** One eicons copy (Elementor's). Elementor's icon-list and social-icons CSS loaded once in <head> instead of Header Footer Elementor's older copies plus Elementor's own.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: footer icons unchanged in screenshots. Simulation: footer icons and social icons unchanged (pixel diff).
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### C9. Livvic from Google Fonts and from Elementor's local copy

- **Status:** Fixed and verified
- **Revalidated:** Confirmed, and more: Montserrat (requested, never used), Roboto and Roboto Slab (Elementor defaults, 118 KB of CSS, never used).
- **Action:** Only Elementor's local Livvic is loaded (all weights and styles). Google Fonts request, Roboto and Roboto Slab removed.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: Livvic loads on home, service and blog pages (after staging's livvic.css was pointed at staging's own font files). Simulation: Livvic loads on home, service and blog pages; no request to fonts.googleapis.com.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

### Theme, footer and caching

#### T1. wp_footer() commented out in footer.php; get_footer() commented out in new-homepage.php

- **Status:** Fixed and verified
- **Revalidated:** Confirmed in code. The site works only because Header Footer Elementor replaces footer.php; with that plugin off, no footer scripts (and no popup) would print. The homepage's own get_footer() was commented out because front-page.php already calls it.
- **Action:** footer.php calls wp_footer() again (back-to-top link points to #page). The homepage template calls get_header()/get_footer() only when it is not the front page, so the front page can't print them twice and the template still works on any other page.
- **Files:** `wp-content/themes/indigenetech-child/footer.php`, `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`
- **Evidence:** Staging: one popup and a footer on all 29 pages (the footer.php path itself was verified on the local install). Local WordPress without a Header Footer Elementor footer: footer scripts and popup now print (the original theme printed none); homepage has exactly one doctype, header, footer and popup.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### T2. time() versioning for common.css / newhome.css

- **Status:** Fixed and verified
- **Revalidated:** Reproduced on live: a new ?ver= on every request (e.g. 1795092084 then 1795092085), so browsers re-download both files on every page view. custom.css was versioned by the WordPress version, so edits did not bust caches.
- **Action:** filemtime() versions for common.css, newhome.css (branch), custom.css, parent style.css, why-choose files. .htaccess sends 'public, max-age=31536000, immutable' for versioned static files and one week for unversioned ones.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`, `.htaccess`
- **Evidence:** Staging: versioned CSS returns 'public, max-age=31536000, immutable' through Cloudflare. Apache 2.4 test: ?ver= assets get the 1-year header, unversioned get 1 week, ?version= is not mistaken for ?ver=.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### T3. Duplicate wp_footer hook for the popup

- **Status:** Fixed and verified
- **Revalidated:** Confirmed in code.
- **Action:** One hook (branch fix).
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: exactly one popup per page. Local WordPress: exactly one #itechPopupOverlay.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.


## Found during revalidation

#### N1. Homepage FAQ throws a TypeError on every open

- **Status:** Fixed and verified
- **Revalidated:** Reproduced: 'Cannot read properties of null (reading classList)' on each question opened (the script expects an <i> icon; the markup uses an SVG).
- **Action:** Null guard; aria-expanded kept in sync.
- **Files:** `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`
- **Evidence:** Staging: FAQ opens without errors. Simulation: 0 errors after opening 3 questions; one item open at a time.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### N2. Slick carousel loaded from jsDelivr inside the page body

- **Status:** Fixed and verified
- **Revalidated:** Reproduced once during the baseline crawl: jsDelivr refused the file (ERR_BLOCKED_BY_RESPONSE) and the homepage's mobile sliders failed with '$slider.slick is not a function'.
- **Action:** Self-hosted copy of slick-carousel 1.8.1 (MIT, licence included), enqueued only with the homepage template.
- **Files:** `wp-content/themes/indigenetech-child/assets/vendor/slick/`, `wp-content/themes/indigenetech-child/functions.php`, `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`
- **Evidence:** Staging: Slick served from the theme; mobile sliders initialise. Simulation and local WordPress: mobile sliders initialise; no request to jsDelivr.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### N3. WordPress emoji script and styles on every page (~25 KB)

- **Status:** Fixed and verified
- **Revalidated:** Confirmed.
- **Action:** Removed; browsers render emoji natively.
- **Files:** `wp-content/themes/indigenetech-child/functions.php`
- **Evidence:** Staging: no emoji script. Simulation: no emoji requests, no visual change.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### N4. Most text asks for the Poppins font, which never loads

- **Status:** Requires business approval
- **Revalidated:** Confirmed: Poppins is the first font for most body text (212 elements on the homepage) but no Poppins file is requested; visitors see their system's sans-serif. A local copy exists in uploads/elementor/google-fonts/poppins.css.
- **Action:** None: loading Poppins changes how most of the site looks.
- **Evidence:** Computed styles vs loaded fonts on home, service and blog pages.
- **Next check:** Owner/designer decides: load Poppins, or switch the CSS to Livvic.

#### N5. Desktop mega menu can't be opened with the keyboard

- **Status:** Fixed and verified
- **Revalidated:** Reproduced (hover only).
- **Action:** Opens on :focus-within as well (see B3).
- **Files:** `wp-content/themes/indigenetech-child/assets/css/common.css`
- **Evidence:** Staging: keyboard focus opens the mega menu. Simulation: keyboard focus opens it.
- **Next check:** Verified on staging 20 Nov 2026. Live: run the same deploy and checks.

#### N6. Focus outline removed from every link (a { outline: none !important })

- **Status:** Requires business approval
- **Revalidated:** Confirmed (site-wide rule).
- **Action:** Kept as is (moved to common.css) because removing it visibly changes focus rings across the site.
- **Evidence:** —
- **Next check:** Recommend allowing a visible focus style for keyboard users.

#### N7. /.webmcp/bridge.js (74 KB) injected into every page

- **Status:** Blocked by technical dependency
- **Revalidated:** Confirmed in the HTML head; not produced by any file in the repository (not a theme or plugin in this repo).
- **Action:** None possible from the repo.
- **Evidence:** grep of the repository.
- **Next check:** Hosting/Cloudflare owner identifies what injects it and whether it is wanted.

#### N8. ElementsKit widget CSS (153 KB, ~2% used) loads on every page

- **Status:** Blocked by technical dependency
- **Revalidated:** Confirmed. It is one combined file for all ElementsKit widgets; the footer's post-list widget needs it.
- **Action:** None.
- **Evidence:** Coverage.
- **Next check:** Would need ElementsKit per-widget CSS or replacing the footer widget.

#### N9. Block-library CSS (128 KB, ~1% used) on the blog and posts

- **Status:** Intentionally retained (required)
- **Revalidated:** Confirmed. Posts contain some block markup, so it is kept there; removed on the homepage only (J3).
- **Action:** Kept on blog/posts.
- **Evidence:** Coverage; block classes present in post HTML.
- **Next check:** —

#### N10. Indexable pages outside the sitemap

- **Status:** Requires business approval
- **Revalidated:** /blog/page/2/ and /blog/category/uncategorized/ are 'index, follow' but not in the sitemap. Search, author and 404 pages are noindex.
- **Action:** Both included in the verification (no errors, no overflow, no placeholder links after the change).
- **Evidence:** Before/after checks at desktop and mobile.
- **Next check:** SEO decision: noindex the Uncategorized category archive in Rank Math, or add a real category.

#### N11. No privacy policy page

- **Status:** Requires business approval
- **Revalidated:** /privacy-policy/ returns 404 while the site collects names and emails through HubSpot forms.
- **Action:** None (legal content).
- **Evidence:** HTTP check.
- **Next check:** Owner provides a privacy policy.

#### N12. Homepage copy change from the earlier branch: "more sweaty" → "steadier"

- **Status:** Requires business approval
- **Revalidated:** The live sentence reads 'better, bigger, and more sweaty in the long run'.
- **Action:** Changed on the merged branch (earlier session).
- **Files:** `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php`
- **Evidence:** Visible in the before/after screenshot.
- **Next check:** Copy changes need the owner's approval; confirm or revert before deploy.


## Database and owner items

#### D1. Live Site Title blank (titles read 'Contact us -')

- **Status:** Requires business approval
- **Revalidated:** Still blank on live; fixed on staging. Side effect seen on staging: with a Site Title set, the header prints 'IndigeneTech' under the logo (26 px taller header).
- **Action:** Settings → General on live.
- **Evidence:** Live <title>: 'Contact us -'.
- **Next check:** Owner approval; decide whether the title text should show under the logo before setting it on live.

#### D2. Schema: duplicate Organization/LocalBusiness, invalid FAQ JSON-LD on one post, indigenetech.local image URL

- **Status:** Requires business approval
- **Revalidated:** Not re-tested in depth: the child theme prints per-page schema stored in the database (meta box), and Rank Math adds its own.
- **Action:** Database edits; credential wording must stay exactly as published.
- **Evidence:** —
- **Next check:** Owner approval.

#### D3. Missing image alt text (~11 images per page)

- **Status:** Requires business approval
- **Revalidated:** Not changed (content).
- **Action:** Content edits in Elementor.
- **Evidence:** —
- **Next check:** Owner approval.

#### D4. Staging is public and leaks to live

- **Status:** Requires business approval
- **Revalidated:** Confirmed: noindex header present, no password; header/footer links, 5 assets, GA4 and HubSpot point at live.
- **Action:** Cloudflare Access or basic auth; database URL clean-up on staging.
- **Evidence:** curl -I staging.
- **Next check:** Owner action.

#### D5. Secrets in git history; staging passwords shared in chat

- **Status:** Requires business approval
- **Revalidated:** wp-config.php now reads secrets from the environment or wp-config-local.php (commits 82cec84, 680ac54).
- **Action:** Rotate the database password and the 8 salts, make the repository private, rotate staging SSH/phpMyAdmin passwords.
- **Files:** `wp-config.php`, `.gitignore`, `.dockerignore`
- **Evidence:** —
- **Next check:** Owner action before deploying this branch (the site stops with 'Configuration error' until values are supplied).

#### D6. Staging: PHP cannot write to wp-content/uploads

- **Status:** Requires business approval
- **Revalidated:** Found during the staging deploy: uploads is owned by staging_indigenetech_ca (775) while PHP-FPM and Apache run as www-data. Clearing Elementor's CSS left all 14 Elementor stylesheets missing. Workaround in place: Elementor CSS print method set to 'internal' on staging. Media uploads in staging's admin are probably blocked too.
- **Action:** Give www-data group write access to uploads (deploy/staging/README.md), then set the print method back to 'external'.
- **Files:** `deploy/staging/README.md`
- **Evidence:** ls -ld and ps output from the staging server.
- **Next check:** DevOps with sudo. Before the live deploy, confirm live's uploads folder is writable by PHP; the deploy script no longer clears Elementor CSS by default.

## File-level changes

| File | Change | Detail |
|---|---|---|
| `wp-content/themes/indigenetech-child/functions.php` | Modified | Asset clean-up, fonts, Font Awesome swap, menu toggle, link normaliser, homepage-only assets, emoji removal, filemtime versions |
| `wp-content/themes/indigenetech-child/footer.php` | Modified | wp_footer() restored; back-to-top link |
| `wp-content/themes/indigenetech-child/custom-pages/new-homepage.php` | Modified | FA5 classes, FAQ buttons + JS fix, Software card link, CDN tags removed, guarded header/footer |
| `wp-content/themes/indigenetech-child/assets/js/itech-site.js` | Modified | Lazy HubSpot forms, mobile menu toggle (popup + loader from the earlier branch) |
| `wp-content/themes/indigenetech-child/assets/js/why-choose.js` | Rewritten | Slider only, no jQuery |
| `wp-content/themes/indigenetech-child/assets/css/common.css` | Modified | Dead components removed, focus-within menu, 2 rules moved in |
| `wp-content/themes/indigenetech-child/assets/css/custom.css` | Pruned | 10.3 KB → 4.4 KB |
| `wp-content/themes/indigenetech-child/assets/css/newhome.css` | Pruned | 29.3 KB → 23.2 KB; FAQ button reset |
| `wp-content/themes/indigenetech-child/assets/css/why-choose.css` | Pruned | Slider rules only |
| `wp-content/themes/indigenetech-child/assets/vendor/slick/` | Added | slick.min.js, slick.css, LICENSE (1.8.1, MIT) |
| `wp-content/themes/indigenetech-child/assets/js/ (10 files), assets/css/ (2), assets/library/, assets/images/, template-parts/content-download.php` | Deleted | Unreferenced duplicates and leftovers (≈2.4 MB) |
| `wp-content/themes/indigenetech/style.css` | Modified | Missing 01.png reference removed |
| `.htaccess` | Modified | Cache-Control for static assets |

Commits: `1f8be6e` Popup on service pages; HubSpot embed loaded once (earlier session, merged); `82cec84, 680ac54` Secrets moved out of wp-config.php (earlier in this session); `4265f6f` Audit remediation: asset clean-up, links, footer, CSS pruning, caching; `de44ea4` newhome.css on the homepage only; unrendered widget assets dropped there

## Deployment

1. Before anything else: supply the database name, host, user, password and the 8 keys on the server (environment variables or wp-config-local.php), because this branch's wp-config.php no longer contains them. Rotate the old values at the same time.
2. Back up the current theme folders and .htaccess on the server.
3. Copy the child theme with deletions so the removed files go too: rsync -a --delete wp-content/themes/indigenetech-child/ <server>:…/wp-content/themes/indigenetech-child/
4. Copy wp-content/themes/indigenetech/style.css and .htaccess (keep any server-specific lines already in the server's .htaccess).
5. Elementor → Tools → Clear Files & Data; purge the Cloudflare cache.
6. Run the staging checks listed against each finding (the crawl and interaction scripts can be pointed at staging).

## Rollback

| To undo | Do this |
|---|---|
| Everything | git revert de44ea4 4265f6f (and 1f8be6e for the popup/HubSpot changes), then redeploy the child theme, parent style.css and .htaccess. Or restore the backed-up folders. |
| One asset removal | In itech_asset_cleanup() (functions.php) delete the wp_dequeue_* line for that handle. |
| Font Awesome swap | Delete step 3 in itech_asset_cleanup() and restore the cdnjs FA7 <link> in new-homepage.php (or keep the fas classes). |
| Fonts | Delete step 6 in itech_asset_cleanup() and the style_loader_tag filter. |
| newhome.css on every page | Remove the is_page_template() condition in itech_child_common_css(). |
| Homepage widget assets | Remove itech_homepage_unrendered_assets() and its two add_action lines. |
| Menu toggle | Remove the nav_menu_link_attributes filter and the placeholder branch in Menu_Icon_Walker::start_el(). |
| Link normaliser | Remove the two add_filter lines for itech_normalize_internal_links. |
| Lazy HubSpot forms | In itech-site.js, make createVisibleForms() call createForm() on every placeholder immediately. |
| Caching headers | Delete the "IndigeneTech static asset caching" block from .htaccess. |
| Deleted files | git checkout 83f7230 -- <path> restores any of them. |

## How this was verified

- Baseline crawl of the 25 sitemap pages on live at 1366 px and 390 px: status codes, every CSS/JS request, Chrome CSS and JS coverage, console errors, failed requests, timings, links and full-page screenshots. Analytics requests were blocked so tests did not count as visits.
- Rendered-DOM capture of every page after scripts and interactions (popup opened, FAQ, tabs, accordion, mega menu, mobile menu) to drive the CSS pruning.
- Local WordPress 7.0.2 from this repository (same plugins, MariaDB, PHP 8.3) to execute the PHP: enqueues, footer, menu walker, homepage template, shortcodes, PHP warnings. Its pages have no Elementor content, so two local-only artefacts were expected and excluded (elementorFrontendConfig on non-Elementor pages, Livvic unused).
- Live simulation: each live page rewritten in the browser to what the branch outputs (dropped/added asset tags, homepage template diff replayed, HubSpot placeholders, menu toggles, link normaliser) with the changed CSS/JS files served from the branch. Interaction suite, full crawl, weight measurement and screenshots were run against it.
- Extra indexable pages (/blog/page/2/, /blog/category/uncategorized/) plus search, author and 404 templates, before and after.
- External links checked with a browser user agent; .htaccess tested on Apache 2.4.
- Not measured: real-world timings (the simulation adds a hop to every page, and staging runs on a different server from live, so TTFB/FCP/LCP before/after are not comparable; live baseline medians were TTFB ≈ 650 ms, FCP ≈ 1.4 s, LCP ≈ 1.5 s, CLS 0). Measure them on live after deploy.
- Staging, 20 Nov 2026: deploy/staging/deploy.sh and checks.sh run by DevOps (29 pages, 40 internal links, 32 stylesheets, theme files, cache header, PHP lint: 8/8 PASS); browser interaction suite 31/31; full-page screenshots of home, service, contact and blog pages at both widths compared with live.
