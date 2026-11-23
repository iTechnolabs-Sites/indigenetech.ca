# Blog post packages

Each folder holds one IndigeneTech blog post, ready to publish in WordPress. Posts are WordPress content (database), not theme code, so they are not deployed by merging this branch.

| Folder | Post | Status |
|---|---|---|
| `erp-for-economic-development-corporations/` | ERP for Economic Development Corporations: One System or Several? | Published on staging (post ID 11933): https://staging.indigenetech.ca/blog/erp-for-economic-development-corporations/ |
| `data-governance-questions-technology-vendor/` | Data Governance Questions to Ask a Technology Vendor: A Guide for First Nations Organizations | Published on staging (post ID 11934): https://staging.indigenetech.ca/blog/data-governance-questions-technology-vendor/ |

## Publishing a post on live

1. Posts → Add New. Title = the H1 in `publish-notes.md` (section B).
2. Switch the editor to the **Text** tab and paste `post.html` exactly as is (one element per line; don't reformat).
3. Permalink (slug), Category **Business**, Rank Math focus keyword, SEO title and meta description: from `publish-notes.md` section B.
4. Add a featured image matching the concept and alt text in section B.
5. Preview: check the table of contents (left sidebar) and the FAQ section at the end, then publish.

Alternatively, export the reviewed posts from staging (Tools → Export → Posts) and import them on live.

## Verified on staging (23 Nov 2026)

Checked as a logged-out visitor: Rank Math SEO title and meta description, one H1, table of contents (8 and 10 entries), all 5 FAQ questions rendered with valid FAQPage structured data, no stray line breaks. The Rank Math FAQ block works in the Classic editor on this site. WordPress re-encodes quotes and angle brackets inside the FAQ block as `\u0022`, `\u003c` when saving; that is expected.

Still to do before live: featured images (concepts and alt text in each `publish-notes.md`).

## Published on live (23 Nov 2026)

| Post | Live post ID | URL |
|---|---|---|
| ERP for Economic Development Corporations | 11931 | https://www.indigenetech.ca/blog/erp-for-economic-development-corporations/ |
| Data Governance Questions to Ask a Technology Vendor | 11932 | https://www.indigenetech.ca/blog/data-governance-questions-technology-vendor/ |

Verified logged out: Rank Math title and description, one H1, table of contents, 5 FAQs rendered with valid FAQPage schema, internal links 200, both URLs in post-sitemap.xml.

Note: posts created through the WordPress REST API don't clear Rank Math's sitemap cache (its cache watcher only runs in wp-admin and cron). Re-saving any post from the editor clears it.

Remaining: add featured images on live and staging.
