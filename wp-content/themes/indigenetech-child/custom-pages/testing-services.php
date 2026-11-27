<?php
/* Template Name: Testing Services */
get_header();
?>

<style>
.ist-card__img img{
    display:block;
    width:100%;
    height:100%;
    object-fit:cover;
}
.ist-page { overflow: hidden; }
.ist-page *, .ist-page *::before, .ist-page *::after { box-sizing: border-box; }
.ist-page .ist-container { width: 100%; max-width: 1230px; margin: 0 auto; padding: 0 15px; }
.ist-page h1, .ist-page h2, .ist-page h3, .ist-page p { margin: 0; }

/* Button */
.ist-page .indigene-hero-button { display: inline-flex; align-items: center; justify-content: center; gap: 12px; margin: 30px 0 0; padding: 12px 14px 12px 28px; border: 0; border-radius: 6px; background: #f67a5a; color: #fff; font-size: 16px; font-weight: 600; line-height: 1.4; cursor: pointer; transition: background .25s; }
.ist-page .indigene-hero-button:hover, .ist-page .indigene-hero-button:focus-visible { background: #e5623f; color: #fff; }
.ist-page .indigene-hero-button svg { width: 28px; height: 28px; flex: 0 0 28px; transition: transform .25s; }
.ist-page .indigene-hero-button:hover svg { transform: translateX(3px); }

/* Banner */
.ist-page .ist-hero { padding: 90px 0; background: linear-gradient(rgba(6,30,35,.93), rgba(12,55,63,.91)), url("https://indigenetech.ca/wp-content/uploads/2025/12/app-development-bottom-cta-banner.webp") center / cover no-repeat; text-align: center; }
.ist-page .ist-hero h1 { max-width: 1040px; margin: 0 auto; color: #fff; font-size: 40px; line-height: 1.18; font-weight: 700; }
.ist-page .ist-hero p { max-width: 1000px; margin: 24px auto 0; color: #e4f3f4; font-size: 18px; line-height: 1.7; }

/* Services */
.ist-page .ist-services { padding: 65px 0; background: #fff; }
.ist-page .ist-heading { margin: 0 auto 35px; text-align: center; }
.ist-page .ist-heading h2 { color: #0d1415; font-size: 38px; line-height: 1.25; font-weight: 700; }
.ist-page .ist-heading p { margin-top: 18px; color: #56666a; font-size: 18px; line-height: 1.7; }
.ist-page .ist-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 30px; }
.ist-page .ist-card { display: flex; flex-direction: column; overflow: hidden; border: 1px solid #d5e9eb; border-radius: 20px; background: #fff; box-shadow: 0 12px 38px rgba(11,46,52,.12); }
.ist-page .ist-card__img { aspect-ratio: 10 / 7; background: #c9eced; }
.ist-page .ist-card__img svg { display: block; width: 100%; height: 100%; object-fit: cover; }
.ist-page .ist-card__body { display: flex; flex: 1; flex-direction: column; align-items: flex-start; padding: 28px; }
.ist-page .ist-card h3 { margin-bottom: 12px; color: #0d1415; font-size: 22px; line-height: 1.35; font-weight: 700; }
.ist-page .ist-card p { padding-bottom: 24px; color: #56666a; font-size: 16px; line-height: 1.7; }
.ist-page .ist-card__btn { display: flex; align-self: stretch; align-items: center; justify-content: space-between; gap: 10px; margin-top: auto; padding: 8px 8px 8px 18px; border: 1px solid #bfe4e6; border-radius: 50px; background: #e6f6f7; color: #195e69; font-size: 15px; font-weight: 600; line-height: 1.35; }
.ist-page .ist-card__btn i { display: flex; flex: 0 0 36px; height: 36px; align-items: center; justify-content: center; border-radius: 50%; background: #195e69; color: #fff; }

/* CTA */
.ist-page .ist-cta { position: relative; overflow: hidden; padding: 96px 0; background: #195e69; text-align: center; }
.ist-page .ist-cta__dec { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; }
.ist-page .ist-cta .ist-container { position: relative; }
.ist-page .ist-cta h2 { max-width: 800px; margin: 0 auto; color: #fff; font-size: 42px; line-height: 1.25; font-weight: 700; }
.ist-page .ist-cta p { max-width: 760px; margin: 20px auto 0; color: #e1f1f2; font-size: 18px; line-height: 1.7; }

/* Tablet */
@media (max-width: 1024px) {
    .ist-page .ist-hero { padding: 90px 0; }
    .ist-page .ist-hero h1 { font-size: 42px; }
    .ist-page .ist-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px; }
}

/* Mobile */
@media (max-width: 640px) {
    .ist-page .ist-hero { padding: 56px 0; }
    .ist-page .ist-hero h1 { font-size: 30px; }
    .ist-page .ist-hero p, .ist-page .ist-heading p, .ist-page .ist-cta p { font-size: 16px; }
    .ist-page .ist-services { padding: 48px 0; }
    .ist-page .ist-heading { margin-bottom: 32px; }
    .ist-page .ist-heading h2, .ist-page .ist-cta h2 { font-size: 27px; }
    .ist-page .ist-grid { grid-template-columns: minmax(0, 1fr); }
    .ist-page .ist-card__body { padding: 22px; }
    .ist-page .ist-cta { padding: 56px 0; }
}

@media (prefers-reduced-motion: reduce) {
    .ist-page .indigene-hero-button, .ist-page .indigene-hero-button svg { transition: none; }
}
</style>

<main class="ist-page">

    <!-- Banner -->
    <section class="ist-hero">
        <div class="ist-container">
            <h1>Professional Testing Services That Build Confidence in Every Release
 </h1>
            <p>Strengthen application quality with comprehensive testing solutions from IndigeneTech. We help Indigenous-owned businesses identify defects, improve application performance, automate testing processes, and deliver reliable digital products with confidence.</p>
            <button class="indigene-hero-button itechOpenPopup">
                <span>Explore Our Testing Capabilities</span>
                <svg width="20" height="20" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.2793 0C26.8223 0 34.5585 7.73629 34.5586 17.2793C34.5586 26.8224 26.8224 34.5586 17.2793 34.5586C7.7362 34.5586 0 26.8224 0 17.2793C9.89569e-05 7.73629 7.73627 0 17.2793 0ZM21.3369 10.208C20.9465 9.81781 20.3134 9.818 19.9229 10.208C19.5324 10.5985 19.5325 11.2315 19.9229 11.6221L24.5801 16.2793H7.56543C7.01321 16.2793 6.56553 16.7271 6.56543 17.2793C6.56543 17.8316 7.01314 18.2793 7.56543 18.2793H24.5801L19.9229 22.9365C19.5325 23.3271 19.5324 23.9601 19.9229 24.3506C20.3133 24.7408 20.9465 24.7408 21.3369 24.3506L27.7012 17.9863C28.0911 17.5959 28.0913 16.9627 27.7012 16.5723L21.3369 10.208Z" fill="white"></path>
                </svg>
            </button>
        </div>
    </section>
    <!-- Services -->
    <section class="ist-services">
        <div class="ist-container">
            <div class="ist-heading">
                <h2>Comprehensive Testing Services for Better Software Performance</h2>
                <p>From test automation and functional validation to AI-powered testing, IndigeneTech helps Indigenous-owned businesses, community organizations, and enterprises improve software quality, minimize risks, and build reliable digital solutions that support their evolving needs.</p>
            </div>
            <div class="ist-grid">
                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/Test-Automation-img.webp" alt="Test Automation" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>Test Automation</h3>
                        <p>As Indigenous-owned businesses and community organizations expand their digital operations, maintaining software quality becomes increasingly important. IndigeneTech helps automate repetitive testing processes, reduce manual effort, and identify defects early, allowing organizations to manage technology more efficiently while keeping testing costs under control.</p>
                        <span class="ist-card__btn">Learn more about Test Automation<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/Functional-Testing-img.webp" alt="Functional Testing" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>Functional Testing</h3>
                        <p>Ensure your digital solutions work the way your business and community members expect. IndigeneTech provides functional testing for websites, applications, and business systems, validating features, workflows, and user interactions to deliver reliable experiences for Indigenous enterprises, nonprofits, and community service organizations.</p>
                        <span class="ist-card__btn">Learn more about Functional Testing<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/Performance-Testing-img.webp" alt="Performance Testing" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>Performance Testing</h3>
                        <p>Growing digital demands require applications that remain reliable under pressure. IndigeneTech evaluates application speed, stability, and scalability to help Indigenous businesses, educational institutions, and community organizations maintain consistent digital services, even as user activity and operational requirements increase.</p>
                        <span class="ist-card__btn">Learn more about Performance Testing<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/Enterprise-Resource-Planning-ERP-Testing.webp" alt="Enterprise Resource Planning (ERP) Testing" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>Enterprise Resource Planning (ERP) Testing</h3>
                        <p>Managing finances, employees, inventory, and community operations requires dependable business systems. IndigeneTech helps Indigenous enterprises, economic development organizations, and community institutions test ERP platforms, validate integrations, and ensure business processes function accurately across departments.</p>
                        <span class="ist-card__btn">Learn more about ERP Testing<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/Test-Advisory-img.webp" alt="Test Advisory" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>Test Advisory</h3>
                        <p>Every organization has different technology requirements and quality assurance challenges. IndigeneTech works with Indigenous-owned businesses, tribal organizations, nonprofits, and community enterprises to evaluate existing testing practices, identify gaps, and develop practical testing strategies that support their operational goals and long-term digital growth.</p>
                        <span class="ist-card__btn">Learn more about Test Advisory<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/Accessibility-Testing-img.webp" alt="Accessibility Testing" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>Accessibility Testing</h3>
                        <p>Digital services should be accessible to everyone, regardless of their abilities or how they access technology. IndigeneTech tests websites and applications against WCAG accessibility standards, helping Indigenous organizations, educational institutions, and public service providers create more inclusive digital experiences for their communities.</p>
                        <span class="ist-card__btn">Learn more about Accessibility Testing<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/Content-Migration-and-QA-img.webp" alt="Content Migration and QA" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>Content Migration and QA</h3>
                        <p>Updating a website or moving to a new digital platform requires careful handling of existing content. IndigeneTech helps Indigenous businesses, community organizations, and cultural institutions migrate website content while checking links, images, formatting, and information accuracy to preserve important resources and maintain a consistent online experience.</p>
                        <span class="ist-card__btn">Learn more about Content Migration<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/User-Acceptance-Testing-img.webp" alt="User Acceptance Testing" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>User Acceptance Testing</h3>
                        <p>Before launching a new digital solution, it is important to confirm that it meets the needs of the people who will use it. IndigeneTech supports user acceptance testing for Indigenous businesses, community organizations, and service providers, helping validate real-world workflows, gather user feedback, and prepare systems for successful deployment.</p>
                        <span class="ist-card__btn">Learn more about User Acceptance Testing<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>

                <article class="ist-card">
                    <div class="ist-card__img">
                        <img src="https://staging.indigenetech.ca/wp-content/uploads/2026/11/AI-Powered-Testing-Services-img.webp" alt="AI-Powered Testing Services" loading="lazy">
                    </div>
                    <div class="ist-card__body">
                        <h3>AI-Powered Testing Services</h3>
                        <p>Make software testing more efficient with AI-driven quality assurance. IndigeneTech helps Indigenous enterprises, technology startups, and community organizations use AI-powered testing to automate repetitive tasks, identify potential defects, analyze application behavior, and improve test coverage while reducing manual testing effort.</p>
                        <span class="ist-card__btn">Learn more about AI-Powered Testing<i aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i></span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="ist-cta">
        <svg class="ist-cta__dec" aria-hidden="true" focusable="false" viewBox="0 0 1440 380" preserveAspectRatio="xMidYMid slice"><circle cx="0" cy="380" r="90" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.42"/><circle cx="0" cy="380" r="160" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.3"/><circle cx="0" cy="380" r="230" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.2"/><circle cx="0" cy="380" r="300" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.12"/><circle cx="0" cy="380" r="48" fill="#c5ebec" opacity=".16"/><circle cx="1440" cy="0" r="90" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.42"/><circle cx="1440" cy="0" r="160" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.3"/><circle cx="1440" cy="0" r="230" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.2"/><circle cx="1440" cy="0" r="300" fill="none" stroke="#c5ebec" stroke-width="2" opacity="0.12"/><circle cx="1440" cy="0" r="48" fill="#c5ebec" opacity=".16"/><circle cx="150" cy="52" r="3" fill="#c5ebec" opacity=".45"/><circle cx="150" cy="72" r="3" fill="#c5ebec" opacity=".45"/><circle cx="150" cy="92" r="3" fill="#c5ebec" opacity=".45"/><circle cx="150" cy="112" r="3" fill="#c5ebec" opacity=".45"/><circle cx="170" cy="52" r="3" fill="#c5ebec" opacity=".45"/><circle cx="170" cy="72" r="3" fill="#c5ebec" opacity=".45"/><circle cx="170" cy="92" r="3" fill="#c5ebec" opacity=".45"/><circle cx="170" cy="112" r="3" fill="#c5ebec" opacity=".45"/><circle cx="190" cy="52" r="3" fill="#c5ebec" opacity=".45"/><circle cx="190" cy="72" r="3" fill="#c5ebec" opacity=".45"/><circle cx="190" cy="92" r="3" fill="#c5ebec" opacity=".45"/><circle cx="190" cy="112" r="3" fill="#c5ebec" opacity=".45"/><circle cx="210" cy="52" r="3" fill="#c5ebec" opacity=".45"/><circle cx="210" cy="72" r="3" fill="#c5ebec" opacity=".45"/><circle cx="210" cy="92" r="3" fill="#c5ebec" opacity=".45"/><circle cx="210" cy="112" r="3" fill="#c5ebec" opacity=".45"/><circle cx="230" cy="52" r="3" fill="#c5ebec" opacity=".45"/><circle cx="230" cy="72" r="3" fill="#c5ebec" opacity=".45"/><circle cx="230" cy="92" r="3" fill="#c5ebec" opacity=".45"/><circle cx="230" cy="112" r="3" fill="#c5ebec" opacity=".45"/><circle cx="250" cy="52" r="3" fill="#c5ebec" opacity=".45"/><circle cx="250" cy="72" r="3" fill="#c5ebec" opacity=".45"/><circle cx="250" cy="92" r="3" fill="#c5ebec" opacity=".45"/><circle cx="250" cy="112" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1190" cy="265" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1190" cy="285" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1190" cy="305" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1190" cy="325" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1210" cy="265" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1210" cy="285" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1210" cy="305" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1210" cy="325" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1230" cy="265" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1230" cy="285" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1230" cy="305" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1230" cy="325" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1250" cy="265" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1250" cy="285" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1250" cy="305" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1250" cy="325" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1270" cy="265" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1270" cy="285" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1270" cy="305" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1270" cy="325" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1290" cy="265" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1290" cy="285" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1290" cy="305" r="3" fill="#c5ebec" opacity=".45"/><circle cx="1290" cy="325" r="3" fill="#c5ebec" opacity=".45"/></svg>
        <div class="ist-container">
            <h2>Let's Strengthen Your Software Quality Together</h2>
            <p>Connect with IndigeneTech to discuss your testing needs, identify quality gaps, and find practical solutions that help your Indigenous business deliver reliable digital experiences.</p>
            <button class="indigene-hero-button itechOpenPopup">
                <span>Contact Us</span>
                <svg width="20" height="20" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.2793 0C26.8223 0 34.5585 7.73629 34.5586 17.2793C34.5586 26.8224 26.8224 34.5586 17.2793 34.5586C7.7362 34.5586 0 26.8224 0 17.2793C9.89569e-05 7.73629 7.73627 0 17.2793 0ZM21.3369 10.208C20.9465 9.81781 20.3134 9.818 19.9229 10.208C19.5324 10.5985 19.5325 11.2315 19.9229 11.6221L24.5801 16.2793H7.56543C7.01321 16.2793 6.56553 16.7271 6.56543 17.2793C6.56543 17.8316 7.01314 18.2793 7.56543 18.2793H24.5801L19.9229 22.9365C19.5325 23.3271 19.5324 23.9601 19.9229 24.3506C20.3133 24.7408 20.9465 24.7408 21.3369 24.3506L27.7012 17.9863C28.0911 17.5959 28.0913 16.9627 27.7012 16.5723L21.3369 10.208Z" fill="white"></path>
                </svg>
            </button>
        </div>
    </section>

</main>

<?php get_footer(); ?>
