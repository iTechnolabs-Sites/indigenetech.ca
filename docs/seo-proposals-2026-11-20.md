# IndigeneTech SEO change proposals (20 Nov 2026)

Content and settings changes that need the owner's approval. Code-side SEO fixes are already on branch `claude/bold-mccarthy-st4ze4`. Reply with the IDs you approve (e.g. “approve S1–S4, T1–T9, A1–A7”), edits to any wording, or “no” per item. Approved items go to staging first.

## Site-wide settings

Settings in WordPress or Rank Math; each fixes many pages at once.

| ID | Where | Now | Proposed | Why |
|---|---|---|---|---|
| S1 | Site Title (Settings → General) | (blank). Titles read “About Us -”, “Contact us -”, “Blog -”; social shares show no site name. | IndigeneTech | Completes every title that ends with the site name. The header no longer prints the title under the logo (fixed in code), so nothing changes visually. Already set on staging. |
| S2 | Rank Math knowledge graph (Titles & Meta → Local SEO) | Person, name “Indigenous owned business”. Shows as og:site_name and as a Person in every page's schema. | Organization, name “IndigeneTech”, logo = site logo | Google reads the business as a person today. Already done on staging; wording of any credential claims is not touched. |
| S3 | Default share image (Titles & Meta → Global → OpenGraph Thumbnail) | None. Code now falls back to the homepage banner (1024×911 WebP). | A 1200×630 JPG with the logo on brand colours (design to supply) | The standard size for Facebook, LinkedIn and X previews; JPG works everywhere. |
| S4 | Uncategorized category archive (Titles & Meta → Categories) | /blog/category/uncategorized/ is indexable, not in the sitemap, and lists the same posts as /blog/. | noindex the category archives, or create 3–4 real categories and assign posts | Avoids a thin duplicate of /blog/ in search results. |

## Schema (structured data)

Values are kept exactly as published; only the type and how entities connect change.

| ID | Where | Now | Proposed | Why |
|---|---|---|---|---|
| SC1 | Homepage schema type (Rank Math, front page) | Article, authored by a Person, with “Written by” / “Time to read: 12 minutes” labels in the X card. | WebPage (no Article), connected to the Organization from S2 | A homepage isn't an article; the reading-time label looks odd in shared links. |
| SC2 | Homepage: theme schema box (Organization + LocalBusiness blocks) | Two separate entities for the same business, plus Rank Math's own (three in total), none linked to each other. | One LocalBusiness block with "@id": "https://www.indigenetech.ca/#organization", keeping today's name, logo, phone, address and social links; delete the separate Organization block | Google sees one business with one address and phone instead of three unrelated entities. |
| SC3 | 10 service pages: schema type (Rank Math) | Article with a Person author. | Service: name = page H1, description = meta description, provider = the Organization, areaServed = Canada | Describes what the page is (a service), eligible for richer results; keeps the existing FAQ schema. |

## Titles and meta descriptions

Proposed text reuses wording already on the page; no new claims.

| ID | Where | Now | Proposed | Why |
|---|---|---|---|---|
| T1 | /about-us/ title | About Us - | About Us \| IndigeneTech | Fixed by S1 alone if you prefer the “-” separator: “About Us - IndigeneTech”. |
| T2 | /contact-us/ title | Contact us - | Contact Us \| IndigeneTech | Same as T1. |
| T3 | /contact-us/ description | Whether you’re looking for more information, answers to any questions you may have, or getting created an effective solution for your instructional needs, we… | Contact IndigeneTech in Fort McMurray, Alberta. Call +1 (825) 901-9111 or send a message and our team will get back to you within 24 hours. (139 characters) | Current text is template copy (“instructional needs”) and is cut off. Address, phone and 24-hour reply are already published on the site. |
| T4 | /blog/ title | Blog - | Blog \| IndigeneTech |  |
| T5 | /blog/ description | (none) | Articles from IndigeneTech on software, AI, cloud and digital growth for Indigenous businesses and communities in Canada. (121 characters) | The only page with no description. |
| T6 | /blog/indigenous-owned-and-operated-tech-businesses/ title | Why Indigenous Owned and Operated Tech Businesses Are Essential for Sustainable Development in Canada? (102 characters) | Why Indigenous Owned and Operated Tech Businesses Matter in Canada (64 characters) | Google cuts titles at about 60 characters; the H1 can stay as it is. |
| T7 | /blog/indigenous-owned-business/ title | Indigene Tech - Indigenous-owned Business Providing IT Consulting Services (74 characters) | IndigeneTech: Indigenous-owned Business Providing IT Consulting (63 characters) | Shorter, and spells the brand as one word like everywhere else. |
| T8 | /blog/indigenous-owned-business/ description and H1 | Description says “IndgineTech”; H1 says “Indigene Tech”. | “IndigeneTech” in both | Brand-name typos. |
| T9 | /blog/how-technology-is-helping-indigenous-businesses-grow/ description | This blog explores how technology can help Indigenous businesses thrive. (73 characters) | How software, cloud tools and automation help Indigenous businesses work more efficiently, reach more customers and grow. (121 characters) | Too short to earn the click; built from the post's own section topics. |

## Image alt text

Empty alt on images that carry content. Decorative menu and footer images keep empty alt (correct).

| ID | Where | Now | Proposed | Why |
|---|---|---|---|---|
| A1 | Site logo (all pages) | indigenetech-logo | IndigeneTech | Read out by screen readers and used by Google Images. |
| A2 | /blog/digital-tools-empowering-indigenous-communities-entrepreneurs/: 3 images | (empty) | “Benefits of digital tools for Indigenous communities” · “Challenges and considerations for digital tools” · “Best practices that help digital tools succeed” | Taken from the section each image illustrates. |
| A3 | /blog/guide-to-mobile-app-development/ (and its card on /blog/) | (empty) | “A complete guide to mobile app development” |  |
| A4 | /blog/how-to-showcase-your-indigenous-business-to-a-global-market/: 2 images | (empty) | “Why going global matters for Indigenous entrepreneurs” · “Strategies to showcase your Indigenous business to the world” |  |
| A5 | /blog/indigenous-owned-and-operated-tech-businesses/: 4 images | (empty) | “Why Indigenous owned and operated businesses create longer-term impact” · “How Indigenous operated teams work in a practical, community-ready way” · “How social sustainability helps services fit real needs” · “Where Indigenous owned and operated tech businesses help most in Canada” |  |
| A6 | /blog/indigenous-tech-solutions-shaping-the-future-of-innovation/: 3 images | (empty) | “The future of innovation through Indigenous technology solutions” · “Real-world examples of Indigenous technology solutions” · “How Indigenous technology solutions influence global innovation” |  |
| A7 | Service pages: 3 images | (empty) | /custom-vr-development/: “AR and VR development” · /it-consulting/: “IT consulting support and continuous optimisation” · /web-development/: “QA and testing” |  |

## Links and pages

| ID | Where | Now | Proposed | Why |
|---|---|---|---|---|
| L1 | Broken external link on /blog/indigenous-owned-business/ | itenterprise.co.uk/bespoke-software-go-bespoke/ (404) | Remove the link, or point it to a current source you choose |  |
| L2 | CCIB link in /blog/indigenetech-joins-ccib/ | http://www.ccib.ca/ | https://www.ccib.ca/ | Same page over HTTPS; link text unchanged. |
| L3 | Privacy policy | /privacy-policy/ returns 404; forms collect names, emails and phone numbers. | Publish a privacy policy and link it in the footer and next to forms | Legal content: owner or counsel to provide. |

