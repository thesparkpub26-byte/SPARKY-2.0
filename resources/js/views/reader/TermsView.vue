<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <article class="legal-content">
        <h1 class="section-headline">Terms &amp; Conditions</h1>
        <p class="legal-updated">Last updated: September 2026</p>

        <p>
          By using the TheSPARK website you agree to these terms. TheSPARK is the official student publication of
          Camarines Sur Polytechnic Colleges (CSPC). If you do not agree, please do not use the site.
        </p>

        <h2>Using the site</h2>
        <ul>
          <li>You may read, share links to, and discuss our stories for personal, non-commercial purposes.</li>
          <li>Do not attempt to disrupt the site, access accounts or areas you are not authorized to use, or scrape it at a rate that affects other readers.</li>
          <li>Staff accounts are issued by the publication and are for the named person only. Keep your credentials private.</li>
        </ul>

        <h2>Accounts</h2>
        <p>
          You are responsible for the accuracy of the information you provide and for activity under your account.
          We may suspend or remove accounts that violate these terms.
        </p>

        <h2>Content and copyright</h2>
        <p>
          Articles, photographs, illustrations, videos and published issues are the work of TheSPARK staff and
          contributors and are protected by copyright. You may quote short excerpts with clear credit and a link
          back to the original. Reproducing full stories, images or issues without written permission from the
          Editor-in-Chief is not allowed.
        </p>

        <h2>Accuracy</h2>
        <p>
          We work to report accurately and fairly. If you spot an error, tell us and we will review and correct it
          where warranted. Opinion pieces reflect their authors' views, not necessarily those of TheSPARK or CSPC.
        </p>

        <h2>Third-party links</h2>
        <p>
          The site links to social platforms and other websites we do not control. We are not responsible for their
          content or practices.
        </p>

        <h2>Liability</h2>
        <p>
          The site is provided "as is". To the extent the law allows, TheSPARK and CSPC are not liable for losses
          arising from your use of the site or from temporary unavailability.
        </p>

        <h2>Changes</h2>
        <p>
          We may update these terms from time to time; continued use after a change means you accept the new terms.
          See also our <router-link to="/privacy-policy">Privacy Policy</router-link>.
        </p>

        <h2>Governing law</h2>
        <p>These terms are governed by the laws of the Republic of the Philippines.</p>
      </article>

      <Footer />
    </main>
  </div>
</template>

<script setup>
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
</script>
