<?php
/* Template Name: About Us */
get_header();
?>

<!--
  IndigeneTech: About Us FAQ block
  Paste this whole block into an Elementor "HTML" widget near the bottom of the About Us page
  (https://www.indigenetech.ca/about-us/). Styles are scoped to .iaf, so the rest of the page won't change.

  SEO SETTINGS FOR THE ABOUT US PAGE (Yoast / Rank Math):
  - SEO title:         About IndigeneTech | Indigenous-Owned Tech Company in Alberta
  - Meta description:  IndigeneTech is an Indigenous-owned technology company in Fort McMurray, Alberta, founded by Paul Morris O'Leary and guided by the values of the Inukshuk.
  - Focus keyphrase:   Indigenous-owned technology company Alberta
-->

<style>
  /* Layout: stone-colour stripe, heading, topic chips, grouped question/answer rows, contact prompt */
  .iaf {
    --iaf-ink: #13212b; --iaf-text: #2f3f4a; --iaf-muted: #5d6d78; --iaf-line: #d5dde2;
    --iaf-paper: #ffffff; --iaf-accent: #0d6b63; --iaf-soft: #e2f0ee;
    --iaf-display: "Archivo", "Helvetica Neue", Arial, sans-serif;
    --iaf-body: "Source Sans 3", "Segoe UI", Roboto, Arial, sans-serif;
    background: var(--iaf-paper); color: var(--iaf-text); font-family: var(--iaf-body); font-size: 1.0625rem; line-height: 1.6;
    max-width: 1240px; margin: 0 auto; box-sizing: border-box;
    padding-left: max(16px, env(safe-area-inset-left, 0px)); padding-right: max(16px, env(safe-area-inset-right, 0px)); padding-block: 2.5rem 3rem;
    display: grid; gap: 1.25rem;
  }
  .iaf *, .iaf *::before, .iaf *::after { box-sizing: border-box; }
  .iaf h2, .iaf h3, .iaf h4 { font-family: var(--iaf-display); color: var(--iaf-ink); margin: 0; text-wrap: balance; }
  .iaf p { margin: 0; }
  .iaf a { color: var(--iaf-accent); text-underline-offset: 3px; }
  .iaf a:focus-visible { outline: 2px solid var(--iaf-accent); outline-offset: 3px; border-radius: 2px; }
  .iaf-stripe { height: 5px; border-radius: 3px; background: linear-gradient(90deg, #e0302f 0 25%, #2d6be0 25% 50%, #1fb5a8 50% 75%, #ddc62a 75% 100%); max-width: 160px; }
  .iaf-head { display: grid; gap: 0.5rem; }
  .iaf-head h2 { font-size: clamp(1.6rem, 3.2vw, 2.2rem); font-weight: 800; letter-spacing: -0.01em; line-height: 1.15; }
  .iaf-head p { color: var(--iaf-muted); max-width: 65ch; }
  .iaf-nav { display: flex; flex-wrap: wrap; gap: 0.5rem; }
  .iaf-nav a { border: 1px solid var(--iaf-line); border-radius: 999px; padding: 0.35rem 0.85rem; font-size: 0.9rem; text-decoration: none; color: var(--iaf-ink); background: var(--iaf-paper); }
  .iaf-nav a:hover { border-color: var(--iaf-accent); color: var(--iaf-accent); }
  .iaf-group { display: grid; scroll-margin-top: 110px; }
  .iaf-group > h3 { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: var(--iaf-accent); padding: 1.25rem 0 0.75rem; }
  .iaf-qa { display: grid; grid-template-columns: minmax(0, 0.85fr) minmax(0, 1.4fr); gap: 0.4rem 2.5rem; padding: 1rem 0; border-top: 1px solid var(--iaf-line); }
  .iaf-qa > * { min-width: 0; }
  .iaf-qa h4 { font-size: 1.02rem; font-weight: 700; line-height: 1.35; }
  .iaf-qa p { font-size: 0.98rem; }
  .iaf-ask { margin-top: 1rem; background: var(--iaf-soft); border-radius: 6px; padding: 1.5rem clamp(1.25rem, 4vw, 2rem); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem 2rem; }
  .iaf-ask h3 { font-size: 1.2rem; }
  .iaf-ask p { color: var(--iaf-muted); }
  .iaf-btn { display: inline-block; background: var(--iaf-accent); color: #ffffff !important; text-decoration: none !important; font-family: var(--iaf-display); font-weight: 700; font-size: 0.95rem; padding: 0.75rem 1.3rem; border-radius: 4px; }
  .iaf-btn:hover { background: #0a564f; }
  @media (max-width: 720px) { .iaf-qa { grid-template-columns: minmax(0, 1fr); } }

  .itab-section,
  .itab-section * {
    box-sizing: border-box;
  }

  .itab-section .itab-row {
    display: flex;
    max-width: 1240px;
    margin: 0 auto;
  }

  .itab-section .itab-col {
    width: 50%;
    padding: 20px;
  }

  .itab-section .itab-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-top: 40px;
  }

  .itab-section .itab-title {
    margin: 0 0 20px;
    padding: 0;
    color: #1f1f1f;
    font-size: 38px;
    font-weight: 600;
    line-height: 1.3;
  }

  .itab-section .itab-text {

    line-height: 28px;
  }

  .itab-section .itab-text:last-child {
    margin-bottom: 0;
  }

  .itab-section .itab-image {
    display: block;
    width: 100%;
    max-width: 92%;
    height: auto;
    margin-top: 50px;
    border-radius: 10px;
  }

  .itab-section .itab-image-right {
    margin-left: auto;
  }
.itab-about {
    padding: 0px 0 50px !important;
}.itab-founder {
    padding: 10px 0 20px;
}
  /* Founder section */
  .itab-section.itab-founder .itab-row {
    max-width: 1140px;
  }

  .itab-section.itab-founder .itab-col {
    padding: 15px;
  }

  .itab-section.itab-founder .itab-image {
    margin-left: auto;
    margin-right: auto;
  }

  /* Tablet */
  @media (max-width: 1024px) {
    .itab-section .itab-title {
      font-size: 26px;
    }
  }

  /* Mobile */
  @media (max-width: 767px) {
    .itab-section .itab-row {
      flex-direction: column;
    }

    .itab-section .itab-col,
    .itab-section.itab-founder .itab-col {
      width: 100%;
      padding: 10px 15px;
    }

    .itab-section .itab-content {
      margin-top: 0;
      text-align: center;
    }

    .itab-section .itab-title {
      margin-bottom: 10px;
    }

    .itab-section .itab-image {
      max-width: 100%;
      margin: 0 auto;
    }

    /* Mobile par founder ki image text se upar */
    .itab-section.itab-founder .itab-media {
      order: -1;
    }
  }
</style>

<!-- About Us -->
<section class="itab-section itab-about icecold-bg">
  <div class="itab-row">
    <div class="itab-col itab-media">
      <img
        class="itab-image itab-image-right"
        src="https://www.indigenetech.ca/wp-content/uploads/2025/09/imgpsh_fullsize_anim.webp"
        alt="Artwork of a polar bear and an Inukshuk under the northern lights">
    </div>

    <div class="itab-col itab-content">
      <h1 class="itab-title">About Us</h1>
      <p class="itab-text">IndigeneTech, as the name suggests, is an Indigenous-owned company, deeply rooted in the cultural heritage. We provide innovative web, mobile, cloud & ERP solutions that guide your business to success. Inspired by ancient stone markers, Inukshuk, we strive to be a reliable compass on your journey of digital transformation.  Our work is grounded in the same values as the markers – survival, community, and harmony! We truly believe in promoting unity and respect for the environment while creating sustainable, innovation solutions that reflect our culture. Our mission is to combine our wisdom that we have gained from our ancestors with our modern approach to deliver tech solutions. Our sole aim is to empower businesses to thrive in the tech-driven world today and in the times ahead! Partner with a culturally-rich company that can empower your business to succeed through community-focused and innovative solutions!
</p>
    </div>
  </div>
</section>

<!-- About the Founder -->
<section class="itab-section itab-founder">
  <div class="itab-row">
    <div class="itab-col itab-content">
      <h2 class="itab-title">About the Founder</h2>
      <p class="itab-text">Paul Morris O’Leary founded IndigeneTech with the vision to use technology as a tool to empower and innovate his “indigenous” community and citizens across Canada. He brings his decades of experience across various industries, including mining, oil, and automotive sectors, to provide unique insights into how technology can enhance efficiency and promote growth. He is truly inspired by the guiding principles of Inukshuk, the traditional stone structure of Inuits. Paul uses his learnings to help businesses navigate the digital landscape with forward-thinking software solutions.</p>
      <p class="itab-text">In 2016, following the Horse River Wildfire, he founded O’Leary ASB to empower the Indigenous community, promoting dignity and equality. His mission focuses on providing opportunities for growth, ensuring that technology serves as a guiding for business success and sustainability.</p>
      <p class="itab-text">Paul well recognizes the importance of digital transformation and is committed to developing scalable and reliable technology that meets today’s needs while anticipating future demands. By leveraging advanced technologies like AI and machine learning, Paul aims to enhance business success and sustainability.</p>
    </div>

    <div class="itab-col itab-media">
      <img
        class="itab-image"
        src="https://www.indigenetech.ca/wp-content/uploads/2025/09/Founder.webp"
        alt="Paul Morris O’Leary, founder of IndigeneTech">
    </div>
  </div>
</section>
<section class="iaf" aria-labelledby="iaf-title">
  <div class="iaf-stripe" aria-hidden="true"></div>
  <header class="iaf-head">
    <h2 id="iaf-title">IndigeneTech FAQ: Indigenous-owned technology company in Canada</h2>
    <p>Answers about IndigeneTech, Indigenous technology services in Canada, Indigenous procurement, technology for First Nations, and our founder, name and values. Last updated November 26, 2026.</p>
  </header>
  <nav class="iaf-nav" aria-label="About FAQ topics">
      <a href="#about-faq-company">Who we are</a>
      <a href="#about-faq-canada">Indigenous technology services in Canada</a>
      <a href="#about-faq-procurement">Indigenous procurement and supplier diversity</a>
      <a href="#about-faq-nations">Technology for First Nations and Indigenous organizations</a>
      <a href="#about-faq-founder">Our founder</a>
      <a href="#about-faq-values">The Inukshuk and our values</a>
      <a href="#about-faq-ownership">Indigenous ownership</a>
      <a href="#about-faq-location">Where we work</a>
      <a href="#about-faq-contact">Working with IndigeneTech</a>
  </nav>
    <div class="iaf-group" id="about-faq-company">
      <h3>Who we are</h3>
      <div class="iaf-qa">
        <h4>What is IndigeneTech?</h4>
        <p>IndigeneTech is an Indigenous-owned technology company in Fort McMurray, Alberta. We help Indigenous businesses, First Nations and M&eacute;tis organizations, Nation-owned corporations and companies across Canada grow with technology, from websites and mobile apps to ERP systems, cloud platforms and AI workflow automation.</p>
      </div>
      <div class="iaf-qa">
        <h4>What is IndigeneTech's mission?</h4>
        <p>IndigeneTech's mission is to combine the wisdom passed down by our ancestors with a modern approach to technology, so the businesses and communities we serve can thrive in a technology-driven world. We see Indigenous businesses as our clients, and we aim to be their long-term technology provider.</p>
      </div>
      <div class="iaf-qa">
        <h4>What does the name IndigeneTech mean?</h4>
        <p>The name IndigeneTech joins two ideas: indigene, an English word for a person native to a place, and tech, for technology. It says what we are in one word: an Indigenous-owned company that builds technology for Indigenous businesses and communities.</p>
      </div>
      <div class="iaf-qa">
        <h4>Who does IndigeneTech work with?</h4>
        <p>IndigeneTech works with Indigenous businesses and entrepreneurs, First Nations and M&eacute;tis organizations, Nation-owned economic development corporations, and companies and public bodies that want an Indigenous technology provider. We work with organizations of every size, from a small business that needs a website to a large corporation running ERP and AI projects.</p>
      </div>
      <div class="iaf-qa">
        <h4>What makes IndigeneTech different from other technology companies?</h4>
        <p>IndigeneTech is Indigenous owned and operated, guided by the values of the Inukshuk, and based in the Wood Buffalo region. Clients get one provider for planning, design, build, hosting and support, a founder who knows the region's industries, and systems built so the community keeps control of its data.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-canada">
      <h3>Indigenous technology services in Canada</h3>
      <div class="iaf-qa">
        <h4>Who are the top Indigenous-owned technology companies in Canada?</h4>
        <p>IndigeneTech is an Indigenous-owned technology company in Fort McMurray, Alberta, offering apps, websites, ERP, cloud and AI services to clients across Canada. When comparing Indigenous tech companies, check for an Indigenous Business Directory listing, CCIB certification and the services you need. IndigeneTech has both credentials.</p>
      </div>
      <div class="iaf-qa">
        <h4>Which Indigenous-owned companies provide AI services in Canada?</h4>
        <p>IndigeneTech is an Indigenous-owned company that provides AI services in Canada, including AI workflow automation, AI integration with ERP and CRM systems, AI agents, chatbots, and Claude implementation and training. IndigeneTech is CCIB certified and listed in the federal Indigenous Business Directory.</p>
      </div>
      <div class="iaf-qa">
        <h4>Is there an Indigenous-owned IT company that serves Edmonton and Calgary?</h4>
        <p>Yes. IndigeneTech is an Indigenous-owned IT company headquartered in Fort McMurray that serves clients in Edmonton, Calgary and across Alberta. We deliver most work remotely and travel on site for discovery, training and launch.</p>
      </div>
      <div class="iaf-qa">
        <h4>Does IndigeneTech serve First Nations in other provinces?</h4>
        <p>Yes. IndigeneTech serves First Nations, M&eacute;tis organizations and Indigenous businesses anywhere in Canada, from British Columbia to Atlantic Canada and the North. Most projects are delivered remotely, with on-site visits for discovery, training and launch when they add value.</p>
      </div>
      <div class="iaf-qa">
        <h4>Who builds websites for First Nations band offices?</h4>
        <p>IndigeneTech builds websites for First Nations band offices and Nation-owned businesses. A band office website can share news, programs, job postings, contacts and emergency updates, and can include secure forms for members. We build sites that staff can update easily and that work well on phones.</p>
      </div>
      <div class="iaf-qa">
        <h4>Is there an Indigenous-owned cloud services provider in Canada?</h4>
        <p>Yes. IndigeneTech is an Indigenous-owned company that provides cloud migration, hosting and infrastructure on AWS, Microsoft Azure and Google Cloud. We can keep data in Canadian data centre regions for organizations that require it.</p>
      </div>
      <div class="iaf-qa">
        <h4>Is there an Indigenous-owned ERP implementation company in Canada?</h4>
        <p>Yes. IndigeneTech is an Indigenous-owned company that implements ERP systems connecting finance, HR, inventory and operations. We work with Indigenous businesses and economic development corporations that need one clear view across several companies or departments.</p>
      </div>
      <div class="iaf-qa">
        <h4>Is there an Indigenous-owned IT staffing company in Canada?</h4>
        <p>Yes. IndigeneTech is an Indigenous-owned company that provides IT staff augmentation in Canada: developers, testers, designers and other IT professionals who join your team for a project or a set period.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-procurement">
      <h3>Indigenous procurement and supplier diversity</h3>
      <div class="iaf-qa">
        <h4>What is the Indigenous Business Directory?</h4>
        <p>The Indigenous Business Directory (IBD) is the federal registry of Indigenous businesses kept by Indigenous Services Canada. A listing means the business is at least 51% Indigenous owned and controlled, and federal buyers use it to find Indigenous suppliers. IndigeneTech is listed in the IBD.</p>
      </div>
      <div class="iaf-qa">
        <h4>What is a CCIB Certified Indigenous Business?</h4>
        <p>A CCIB Certified Indigenous Business is a company that the Canadian Council for Indigenous Business has confirmed is Indigenous owned and controlled. Buyers use the certification to verify Indigenous suppliers. IndigeneTech is a CCIB Certified Indigenous Business, Member ID 11883.</p>
      </div>
      <div class="iaf-qa">
        <h4>How do companies find Indigenous suppliers for IT and software?</h4>
        <p>Companies find Indigenous IT suppliers through the federal Indigenous Business Directory, CCIB's network of certified Indigenous businesses, and referrals from Indigenous business associations. IndigeneTech is a certified Indigenous IT supplier in Fort McMurray that provides software, apps, cloud, ERP and AI services across Canada.</p>
      </div>
      <div class="iaf-qa">
        <h4>Does buying from IndigeneTech help with CCIB's Progressive Aboriginal Relations (PAR) program?</h4>
        <p>Yes. Companies in CCIB's Progressive Aboriginal Relations (PAR) program report on business development with Indigenous suppliers. Spending with IndigeneTech, a CCIB Certified Indigenous Business, is Indigenous supplier spend that a company can include in that reporting.</p>
      </div>
      <div class="iaf-qa">
        <h4>What is economic reconciliation?</h4>
        <p>Economic reconciliation means closing the economic gap between Indigenous and non-Indigenous people in Canada through fair access to jobs, contracts, capital and ownership. Choosing Indigenous suppliers such as IndigeneTech for technology work is one direct way organizations take part.</p>
      </div>
      <div class="iaf-qa">
        <h4>Can provincial and municipal governments buy from IndigeneTech?</h4>
        <p>Yes. Provincial governments, municipalities, health authorities, school boards and universities can contract IndigeneTech directly or through their supplier diversity and Indigenous procurement programs.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-nations">
      <h3>Technology for First Nations and Indigenous organizations</h3>
      <div class="iaf-qa">
        <h4>How can First Nations use technology to serve members better?</h4>
        <p>First Nations use technology to reach members wherever they live: member apps and portals for news and program applications, online forms instead of paper, text and push alerts for emergencies, and AI assistants that answer common questions. IndigeneTech builds these tools and trains staff to run them.</p>
      </div>
      <div class="iaf-qa">
        <h4>What should an economic development corporation look for in a technology provider?</h4>
        <p>An economic development corporation should look for a technology provider that understands multi-company operations, can integrate finance and reporting across subsidiaries, respects community data ownership and offers long-term support. Indigenous ownership also matters for procurement goals. IndigeneTech offers all of these.</p>
      </div>
      <div class="iaf-qa">
        <h4>How can AI help First Nations administration?</h4>
        <p>AI helps First Nations administration by drafting funding proposals and reports, sorting and answering routine emails, pulling details from forms and PDFs, and answering member questions from policy documents. IndigeneTech sets these tools up with staff approving the results and the Nation keeping control of its data.</p>
      </div>
      <div class="iaf-qa">
        <h4>What is Indigenous data sovereignty?</h4>
        <p>Indigenous data sovereignty is the right of Indigenous Peoples to own, control, access and possess data about their communities, lands and members. In Canada it is often expressed through the First Nations principles of OCAP&reg;. IndigeneTech designs systems so communities keep that control.</p>
      </div>
      <div class="iaf-qa">
        <h4>Can IndigeneTech digitize paper records and forms?</h4>
        <p>Yes. IndigeneTech converts paper records and forms into secure, searchable digital systems. We scan and structure existing records, replace paper forms with online ones, and use AI to pull key details so staff spend less time on data entry.</p>
      </div>
      <div class="iaf-qa">
        <h4>Can apps and websites support Indigenous languages?</h4>
        <p>Yes. Modern apps and websites can display Indigenous languages, including Cree and Inuktitut syllabics, and can include audio for pronunciation. IndigeneTech can build language features such as bilingual menus, word lists and recorded audio into the websites and apps we develop.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-founder">
      <h3>Our founder</h3>
      <div class="iaf-qa">
        <h4>Who is Paul Morris O'Leary?</h4>
        <p>Paul Morris O'Leary is the founder of IndigeneTech. He has decades of experience in the mining, oil and automotive sectors, and founded IndigeneTech to use technology as a tool that empowers Indigenous communities and citizens across Canada.</p>
      </div>
      <div class="iaf-qa">
        <h4>Why did Paul Morris O'Leary start IndigeneTech?</h4>
        <p>Paul Morris O'Leary started IndigeneTech so Indigenous businesses and communities would have a technology company that shares their values and works for their success. His goal is to use software, AI and machine learning to help Indigenous businesses grow and stay sustainable.</p>
      </div>
      <div class="iaf-qa">
        <h4>What is O'Leary ASB?</h4>
        <p>O'Leary ASB is the business Paul Morris O'Leary founded in 2016, after the Horse River Wildfire, to empower the Indigenous community and promote dignity and equality. IndigeneTech carries the same purpose into technology.</p>
      </div>
      <div class="iaf-qa">
        <h4>How is IndigeneTech connected to the Horse River Wildfire?</h4>
        <p>The 2016 Horse River Wildfire forced the evacuation of all of Fort McMurray. After the fire, founder Paul Morris O'Leary founded O'Leary ASB to empower the Indigenous community, and that commitment to rebuilding and resilience led to IndigeneTech.</p>
      </div>
      <div class="iaf-qa">
        <h4>What industry experience does IndigeneTech's founder bring?</h4>
        <p>IndigeneTech's founder Paul Morris O'Leary brings decades of hands-on experience in mining, oil and automotive. He understands how the industries that drive northern Alberta operate, which helps IndigeneTech build technology that fits real operations, remote sites and field teams.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-values">
      <h3>The Inukshuk and our values</h3>
      <div class="iaf-qa">
        <h4>What does the Inukshuk mean?</h4>
        <p>An Inukshuk is a stone figure built by Inuit across the Arctic to guide travellers, mark routes and show places of importance. IndigeneTech takes its inspiration from the Inukshuk and aims to be a reliable guide for clients on their path to digital transformation.</p>
      </div>
      <div class="iaf-qa">
        <h4>Why is there an Inukshuk in the IndigeneTech logo?</h4>
        <p>The IndigeneTech logo builds an Inukshuk from coloured stones inside the company name. It reflects the values we share with these stone markers, survival, community and harmony, and our role as a guide for clients adopting new technology.</p>
      </div>
      <div class="iaf-qa">
        <h4>What values guide IndigeneTech?</h4>
        <p>IndigeneTech is guided by survival, community and harmony, the values of the Inukshuk, along with respect for the environment and sustainable innovation. In our work these become self-determination, community-led decisions, trusted partnerships and practical solutions.</p>
      </div>
      <div class="iaf-qa">
        <h4>How do Indigenous values shape the technology IndigeneTech builds?</h4>
        <p>Indigenous values shape every IndigeneTech project. The community decides what gets built, the organization keeps ownership and control of its data, and we choose practical, long-lasting systems over short-term fixes, so the technology serves the community for years.</p>
      </div>
      <div class="iaf-qa">
        <h4>Does IndigeneTech consider sustainability and the environment?</h4>
        <p>Yes. Respect for the environment is one of the values IndigeneTech was founded on. We build systems that last and scale, so organizations don't need to replace them every few years, and we size cloud hosting so clients use and pay for only what they need.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-ownership">
      <h3>Indigenous ownership</h3>
      <div class="iaf-qa">
        <h4>Is IndigeneTech Indigenous owned and operated?</h4>
        <p>Yes. IndigeneTech is proudly Indigenous owned and operated by founder Paul Morris O'Leary. The company is a Canadian Council for Indigenous Business (CCIB) Certified Indigenous Business and is registered in the federal Indigenous Business Directory.</p>
      </div>
      <div class="iaf-qa">
        <h4>How can I verify IndigeneTech's Indigenous business status?</h4>
        <p>You can verify IndigeneTech in two places: its <a href="https://www.sac-isc.gc.ca/REA-IBD/eng/profile?id=C74FF0A344557D4EA088A6C111F42029&amp;index=1" target="_blank" rel="noopener">Indigenous Business Directory listing</a> with Indigenous Services Canada, and its CCIB Certified Indigenous Business status, Member ID 11883. Both require proof of Indigenous ownership and control.</p>
      </div>
      <div class="iaf-qa">
        <h4>Is IndigeneTech a member of the Canadian Council for Indigenous Business?</h4>
        <p>Yes. IndigeneTech joined the Canadian Council for Indigenous Business (CCIB) and is a CCIB Certified Indigenous Business, Member ID 11883. CCIB is a national organization that promotes Indigenous business and economic reconciliation in Canada.</p>
      </div>
      <div class="iaf-qa">
        <h4>Does hiring IndigeneTech support Indigenous economic development?</h4>
        <p>Yes. When you hire IndigeneTech, your technology spending goes to an Indigenous-owned business based in Fort McMurray. That supports Indigenous economic development and counts toward Indigenous procurement and supplier diversity commitments.</p>
      </div>
      <div class="iaf-qa">
        <h4>How does working with IndigeneTech support reconciliation?</h4>
        <p>Call to Action 92 of the Truth and Reconciliation Commission asks corporate Canada to make sure Indigenous communities gain long-term, sustainable benefits from economic development. Buying technology services from an Indigenous-owned company like IndigeneTech is one practical way to act on it.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-location">
      <h3>Where we work</h3>
      <div class="iaf-qa">
        <h4>Where is IndigeneTech based?</h4>
        <p>IndigeneTech is based at 9906 Sutherland Street, Fort McMurray, Alberta T9H 1V4, in the Regional Municipality of Wood Buffalo in northern Alberta.</p>
      </div>
      <div class="iaf-qa">
        <h4>Does IndigeneTech work outside Fort McMurray?</h4>
        <p>Yes. IndigeneTech works with clients across Alberta and the rest of Canada. We meet on site when it matters, such as discovery, training and launch, and work remotely the rest of the time.</p>
      </div>
      <div class="iaf-qa">
        <h4>Why does being based in Fort McMurray matter to clients?</h4>
        <p>Being based in Fort McMurray puts IndigeneTech close to the oil sands industry and the Indigenous communities of the Wood Buffalo region. We understand local realities such as remote sites, shift work and limited connectivity, and we build technology that works under those conditions.</p>
      </div>
    </div>
    <div class="iaf-group" id="about-faq-contact">
      <h3>Working with IndigeneTech</h3>
      <div class="iaf-qa">
        <h4>What services does IndigeneTech provide?</h4>
        <p>IndigeneTech provides mobile app and website development, custom and enterprise software, ERP, cloud solutions, AI integration and AI workflow automation, software testing, IT consulting and IT staff augmentation. See our <a href="https://www.indigenetech.ca/capability-statement/">capability statement</a> for details.</p>
      </div>
      <div class="iaf-qa">
        <h4>How quickly does IndigeneTech respond to enquiries?</h4>
        <p>IndigeneTech responds to enquiries within 24 hours. Send your details through the contact form, email info@indigenetech.ca or call +1 (825) 901-9111.</p>
      </div>
      <div class="iaf-qa">
        <h4>Does IndigeneTech publish guides for First Nations organizations?</h4>
        <p>Yes. IndigeneTech publishes practical guides on its blog, including Data Governance Questions to Ask a Technology Vendor: A Guide for First Nations Organizations, and ERP for Economic Development Corporations: One System or Several?</p>
      </div>
      <div class="iaf-qa">
        <h4>How do I contact IndigeneTech?</h4>
        <p>Call IndigeneTech at +1 (825) 901-9111, email info@indigenetech.ca, use the <a href="https://www.indigenetech.ca/contact-us/">contact form</a>, or visit us at 9906 Sutherland Street, Fort McMurray, Alberta.</p>
      </div>
    </div>
  <div class="iaf-ask">
    <div>
      <h3>Have a question we didn't answer?</h3>
      <p>We respond within 24 hours. Call +1 (825) 901-9111 or email info@indigenetech.ca.</p>
    </div>
    <a class="iaf-btn" href="https://www.indigenetech.ca/contact-us/">Contact IndigeneTech</a>
  </div>
</section>

<script type="application/ld+json">
{
 "@context": "https://schema.org",
 "@graph": [
  {
   "@type": "ProfessionalService",
   "@id": "https://www.indigenetech.ca/#organization",
   "name": "IndigeneTech",
   "url": "https://www.indigenetech.ca/",
   "logo": "https://www.indigenetech.ca/wp-content/uploads/2024/10/indigenetech-logo.webp",
   "slogan": "Proudly Indigenous owned and operated",
   "telephone": "+1-825-901-9111",
   "email": "info@indigenetech.ca",
   "address": {
    "@type": "PostalAddress",
    "streetAddress": "9906 Sutherland Street",
    "addressLocality": "Fort McMurray",
    "addressRegion": "AB",
    "postalCode": "T9H 1V4",
    "addressCountry": "CA"
   },
   "areaServed": {
    "@type": "Country",
    "name": "Canada"
   },
   "founder": {
    "@id": "https://www.indigenetech.ca/about-us/#founder"
   },
   "sameAs": [
    "https://www.linkedin.com/company/indigenetech/",
    "https://www.facebook.com/people/Indigenetech/61567828784151/",
    "https://www.instagram.com/indigenetech_official/"
   ]
  },
  {
   "@type": "Person",
   "@id": "https://www.indigenetech.ca/about-us/#founder",
   "name": "Paul Morris O'Leary",
   "jobTitle": "Founder",
   "worksFor": {
    "@id": "https://www.indigenetech.ca/#organization"
   },
   "description": "Founder of IndigeneTech, an Indigenous-owned technology company in Fort McMurray, Alberta. Decades of experience in mining, oil and automotive; founded O'Leary ASB in 2016 after the Horse River Wildfire."
  },
  {
   "@type": "FAQPage",
   "@id": "https://www.indigenetech.ca/about-us/#faq",
   "inLanguage": "en-CA",
   "mainEntity": [
    {
     "@type": "Question",
     "name": "What is IndigeneTech?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech is an Indigenous-owned technology company in Fort McMurray, Alberta. We help Indigenous businesses, First Nations and Métis organizations, Nation-owned corporations and companies across Canada grow with technology, from websites and mobile apps to ERP systems, cloud platforms and AI workflow automation."
     }
    },
    {
     "@type": "Question",
     "name": "What is IndigeneTech's mission?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech's mission is to combine the wisdom passed down by our ancestors with a modern approach to technology, so the businesses and communities we serve can thrive in a technology-driven world. We see Indigenous businesses as our clients, and we aim to be their long-term technology provider."
     }
    },
    {
     "@type": "Question",
     "name": "What does the name IndigeneTech mean?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "The name IndigeneTech joins two ideas: indigene, an English word for a person native to a place, and tech, for technology. It says what we are in one word: an Indigenous-owned company that builds technology for Indigenous businesses and communities."
     }
    },
    {
     "@type": "Question",
     "name": "Who does IndigeneTech work with?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech works with Indigenous businesses and entrepreneurs, First Nations and Métis organizations, Nation-owned economic development corporations, and companies and public bodies that want an Indigenous technology provider. We work with organizations of every size, from a small business that needs a website to a large corporation running ERP and AI projects."
     }
    },
    {
     "@type": "Question",
     "name": "What makes IndigeneTech different from other technology companies?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech is Indigenous owned and operated, guided by the values of the Inukshuk, and based in the Wood Buffalo region. Clients get one provider for planning, design, build, hosting and support, a founder who knows the region's industries, and systems built so the community keeps control of its data."
     }
    },
    {
     "@type": "Question",
     "name": "Who are the top Indigenous-owned technology companies in Canada?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech is an Indigenous-owned technology company in Fort McMurray, Alberta, offering apps, websites, ERP, cloud and AI services to clients across Canada. When comparing Indigenous tech companies, check for an Indigenous Business Directory listing, CCIB certification and the services you need. IndigeneTech has both credentials."
     }
    },
    {
     "@type": "Question",
     "name": "Which Indigenous-owned companies provide AI services in Canada?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech is an Indigenous-owned company that provides AI services in Canada, including AI workflow automation, AI integration with ERP and CRM systems, AI agents, chatbots, and Claude implementation and training. IndigeneTech is CCIB certified and listed in the federal Indigenous Business Directory."
     }
    },
    {
     "@type": "Question",
     "name": "Is there an Indigenous-owned IT company that serves Edmonton and Calgary?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech is an Indigenous-owned IT company headquartered in Fort McMurray that serves clients in Edmonton, Calgary and across Alberta. We deliver most work remotely and travel on site for discovery, training and launch."
     }
    },
    {
     "@type": "Question",
     "name": "Does IndigeneTech serve First Nations in other provinces?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech serves First Nations, Métis organizations and Indigenous businesses anywhere in Canada, from British Columbia to Atlantic Canada and the North. Most projects are delivered remotely, with on-site visits for discovery, training and launch when they add value."
     }
    },
    {
     "@type": "Question",
     "name": "Who builds websites for First Nations band offices?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech builds websites for First Nations band offices and Nation-owned businesses. A band office website can share news, programs, job postings, contacts and emergency updates, and can include secure forms for members. We build sites that staff can update easily and that work well on phones."
     }
    },
    {
     "@type": "Question",
     "name": "Is there an Indigenous-owned cloud services provider in Canada?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech is an Indigenous-owned company that provides cloud migration, hosting and infrastructure on AWS, Microsoft Azure and Google Cloud. We can keep data in Canadian data centre regions for organizations that require it."
     }
    },
    {
     "@type": "Question",
     "name": "Is there an Indigenous-owned ERP implementation company in Canada?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech is an Indigenous-owned company that implements ERP systems connecting finance, HR, inventory and operations. We work with Indigenous businesses and economic development corporations that need one clear view across several companies or departments."
     }
    },
    {
     "@type": "Question",
     "name": "Is there an Indigenous-owned IT staffing company in Canada?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech is an Indigenous-owned company that provides IT staff augmentation in Canada: developers, testers, designers and other IT professionals who join your team for a project or a set period."
     }
    },
    {
     "@type": "Question",
     "name": "What is the Indigenous Business Directory?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "The Indigenous Business Directory (IBD) is the federal registry of Indigenous businesses kept by Indigenous Services Canada. A listing means the business is at least 51% Indigenous owned and controlled, and federal buyers use it to find Indigenous suppliers. IndigeneTech is listed in the IBD."
     }
    },
    {
     "@type": "Question",
     "name": "What is a CCIB Certified Indigenous Business?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "A CCIB Certified Indigenous Business is a company that the Canadian Council for Indigenous Business has confirmed is Indigenous owned and controlled. Buyers use the certification to verify Indigenous suppliers. IndigeneTech is a CCIB Certified Indigenous Business, Member ID 11883."
     }
    },
    {
     "@type": "Question",
     "name": "How do companies find Indigenous suppliers for IT and software?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Companies find Indigenous IT suppliers through the federal Indigenous Business Directory, CCIB's network of certified Indigenous businesses, and referrals from Indigenous business associations. IndigeneTech is a certified Indigenous IT supplier in Fort McMurray that provides software, apps, cloud, ERP and AI services across Canada."
     }
    },
    {
     "@type": "Question",
     "name": "Does buying from IndigeneTech help with CCIB's Progressive Aboriginal Relations (PAR) program?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. Companies in CCIB's Progressive Aboriginal Relations (PAR) program report on business development with Indigenous suppliers. Spending with IndigeneTech, a CCIB Certified Indigenous Business, is Indigenous supplier spend that a company can include in that reporting."
     }
    },
    {
     "@type": "Question",
     "name": "What is economic reconciliation?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Economic reconciliation means closing the economic gap between Indigenous and non-Indigenous people in Canada through fair access to jobs, contracts, capital and ownership. Choosing Indigenous suppliers such as IndigeneTech for technology work is one direct way organizations take part."
     }
    },
    {
     "@type": "Question",
     "name": "Can provincial and municipal governments buy from IndigeneTech?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. Provincial governments, municipalities, health authorities, school boards and universities can contract IndigeneTech directly or through their supplier diversity and Indigenous procurement programs."
     }
    },
    {
     "@type": "Question",
     "name": "How can First Nations use technology to serve members better?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "First Nations use technology to reach members wherever they live: member apps and portals for news and program applications, online forms instead of paper, text and push alerts for emergencies, and AI assistants that answer common questions. IndigeneTech builds these tools and trains staff to run them."
     }
    },
    {
     "@type": "Question",
     "name": "What should an economic development corporation look for in a technology provider?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "An economic development corporation should look for a technology provider that understands multi-company operations, can integrate finance and reporting across subsidiaries, respects community data ownership and offers long-term support. Indigenous ownership also matters for procurement goals. IndigeneTech offers all of these."
     }
    },
    {
     "@type": "Question",
     "name": "How can AI help First Nations administration?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "AI helps First Nations administration by drafting funding proposals and reports, sorting and answering routine emails, pulling details from forms and PDFs, and answering member questions from policy documents. IndigeneTech sets these tools up with staff approving the results and the Nation keeping control of its data."
     }
    },
    {
     "@type": "Question",
     "name": "What is Indigenous data sovereignty?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Indigenous data sovereignty is the right of Indigenous Peoples to own, control, access and possess data about their communities, lands and members. In Canada it is often expressed through the First Nations principles of OCAP®. IndigeneTech designs systems so communities keep that control."
     }
    },
    {
     "@type": "Question",
     "name": "Can IndigeneTech digitize paper records and forms?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech converts paper records and forms into secure, searchable digital systems. We scan and structure existing records, replace paper forms with online ones, and use AI to pull key details so staff spend less time on data entry."
     }
    },
    {
     "@type": "Question",
     "name": "Can apps and websites support Indigenous languages?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. Modern apps and websites can display Indigenous languages, including Cree and Inuktitut syllabics, and can include audio for pronunciation. IndigeneTech can build language features such as bilingual menus, word lists and recorded audio into the websites and apps we develop."
     }
    },
    {
     "@type": "Question",
     "name": "Who is Paul Morris O'Leary?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Paul Morris O'Leary is the founder of IndigeneTech. He has decades of experience in the mining, oil and automotive sectors, and founded IndigeneTech to use technology as a tool that empowers Indigenous communities and citizens across Canada."
     }
    },
    {
     "@type": "Question",
     "name": "Why did Paul Morris O'Leary start IndigeneTech?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Paul Morris O'Leary started IndigeneTech so Indigenous businesses and communities would have a technology company that shares their values and works for their success. His goal is to use software, AI and machine learning to help Indigenous businesses grow and stay sustainable."
     }
    },
    {
     "@type": "Question",
     "name": "What is O'Leary ASB?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "O'Leary ASB is the business Paul Morris O'Leary founded in 2016, after the Horse River Wildfire, to empower the Indigenous community and promote dignity and equality. IndigeneTech carries the same purpose into technology."
     }
    },
    {
     "@type": "Question",
     "name": "How is IndigeneTech connected to the Horse River Wildfire?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "The 2016 Horse River Wildfire forced the evacuation of all of Fort McMurray. After the fire, founder Paul Morris O'Leary founded O'Leary ASB to empower the Indigenous community, and that commitment to rebuilding and resilience led to IndigeneTech."
     }
    },
    {
     "@type": "Question",
     "name": "What industry experience does IndigeneTech's founder bring?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech's founder Paul Morris O'Leary brings decades of hands-on experience in mining, oil and automotive. He understands how the industries that drive northern Alberta operate, which helps IndigeneTech build technology that fits real operations, remote sites and field teams."
     }
    },
    {
     "@type": "Question",
     "name": "What does the Inukshuk mean?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "An Inukshuk is a stone figure built by Inuit across the Arctic to guide travellers, mark routes and show places of importance. IndigeneTech takes its inspiration from the Inukshuk and aims to be a reliable guide for clients on their path to digital transformation."
     }
    },
    {
     "@type": "Question",
     "name": "Why is there an Inukshuk in the IndigeneTech logo?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "The IndigeneTech logo builds an Inukshuk from coloured stones inside the company name. It reflects the values we share with these stone markers, survival, community and harmony, and our role as a guide for clients adopting new technology."
     }
    },
    {
     "@type": "Question",
     "name": "What values guide IndigeneTech?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech is guided by survival, community and harmony, the values of the Inukshuk, along with respect for the environment and sustainable innovation. In our work these become self-determination, community-led decisions, trusted partnerships and practical solutions."
     }
    },
    {
     "@type": "Question",
     "name": "How do Indigenous values shape the technology IndigeneTech builds?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Indigenous values shape every IndigeneTech project. The community decides what gets built, the organization keeps ownership and control of its data, and we choose practical, long-lasting systems over short-term fixes, so the technology serves the community for years."
     }
    },
    {
     "@type": "Question",
     "name": "Does IndigeneTech consider sustainability and the environment?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. Respect for the environment is one of the values IndigeneTech was founded on. We build systems that last and scale, so organizations don't need to replace them every few years, and we size cloud hosting so clients use and pay for only what they need."
     }
    },
    {
     "@type": "Question",
     "name": "Is IndigeneTech Indigenous owned and operated?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech is proudly Indigenous owned and operated by founder Paul Morris O'Leary. The company is a Canadian Council for Indigenous Business (CCIB) Certified Indigenous Business and is registered in the federal Indigenous Business Directory."
     }
    },
    {
     "@type": "Question",
     "name": "How can I verify IndigeneTech's Indigenous business status?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "You can verify IndigeneTech in two places: its Indigenous Business Directory listing with Indigenous Services Canada, and its CCIB Certified Indigenous Business status, Member ID 11883. Both require proof of Indigenous ownership and control."
     }
    },
    {
     "@type": "Question",
     "name": "Is IndigeneTech a member of the Canadian Council for Indigenous Business?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech joined the Canadian Council for Indigenous Business (CCIB) and is a CCIB Certified Indigenous Business, Member ID 11883. CCIB is a national organization that promotes Indigenous business and economic reconciliation in Canada."
     }
    },
    {
     "@type": "Question",
     "name": "Does hiring IndigeneTech support Indigenous economic development?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. When you hire IndigeneTech, your technology spending goes to an Indigenous-owned business based in Fort McMurray. That supports Indigenous economic development and counts toward Indigenous procurement and supplier diversity commitments."
     }
    },
    {
     "@type": "Question",
     "name": "How does working with IndigeneTech support reconciliation?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Call to Action 92 of the Truth and Reconciliation Commission asks corporate Canada to make sure Indigenous communities gain long-term, sustainable benefits from economic development. Buying technology services from an Indigenous-owned company like IndigeneTech is one practical way to act on it."
     }
    },
    {
     "@type": "Question",
     "name": "Where is IndigeneTech based?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech is based at 9906 Sutherland Street, Fort McMurray, Alberta T9H 1V4, in the Regional Municipality of Wood Buffalo in northern Alberta."
     }
    },
    {
     "@type": "Question",
     "name": "Does IndigeneTech work outside Fort McMurray?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech works with clients across Alberta and the rest of Canada. We meet on site when it matters, such as discovery, training and launch, and work remotely the rest of the time."
     }
    },
    {
     "@type": "Question",
     "name": "Why does being based in Fort McMurray matter to clients?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Being based in Fort McMurray puts IndigeneTech close to the oil sands industry and the Indigenous communities of the Wood Buffalo region. We understand local realities such as remote sites, shift work and limited connectivity, and we build technology that works under those conditions."
     }
    },
    {
     "@type": "Question",
     "name": "What services does IndigeneTech provide?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech provides mobile app and website development, custom and enterprise software, ERP, cloud solutions, AI integration and AI workflow automation, software testing, IT consulting and IT staff augmentation. See our capability statement for details."
     }
    },
    {
     "@type": "Question",
     "name": "How quickly does IndigeneTech respond to enquiries?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "IndigeneTech responds to enquiries within 24 hours. Send your details through the contact form, email info@indigenetech.ca or call +1 (825) 901-9111."
     }
    },
    {
     "@type": "Question",
     "name": "Does IndigeneTech publish guides for First Nations organizations?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes. IndigeneTech publishes practical guides on its blog, including Data Governance Questions to Ask a Technology Vendor: A Guide for First Nations Organizations, and ERP for Economic Development Corporations: One System or Several?"
     }
    },
    {
     "@type": "Question",
     "name": "How do I contact IndigeneTech?",
     "acceptedAnswer": {
      "@type": "Answer",
      "text": "Call IndigeneTech at +1 (825) 901-9111, email info@indigenetech.ca, use the contact form, or visit us at 9906 Sutherland Street, Fort McMurray, Alberta."
     }
    }
   ]
  }
 ]
}
</script>


<?php get_footer(); ?>
