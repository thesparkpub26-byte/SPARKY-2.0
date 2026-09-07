<template>
  <div class="reader-page">
    <Navbar />

    <main class="main-container">
      <!-- Published Issues Section -->
      <section class="published-issues-section" style="margin-top: 32px; width: 100%;">
        <h1 class="section-headline" style="margin-bottom: 28px;">Published Issues</h1>

        <div class="issues-grid">
          <IssueCard 
            v-for="(item, idx) in issues" 
            :key="idx" 
            :issue="item" 
            @explore="handleExplore"
          />
        </div>
      </section>

      <!-- Subscribe To Our Newsletter Section -->
      <NewsletterCard />

      <!-- Footer Section -->
      <Footer />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Footer from '../../components/Footer.vue';
import NewsletterCard from '../../components/NewsletterCard.vue';
import IssueCard from '../../components/IssueCard.vue';

const issues = [
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER I Volume XLI I No. 1',
    desc: 'The Official Student Community Publication of CSPC I August - December 2021',
    date: 'January 9, 2022'
  },
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER I Volume XLI I No. 1',
    desc: 'The Official Student Community Publication of CSPC I August - December 2021',
    date: 'January 9, 2022'
  },
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER I Volume XLI I No. 1',
    desc: 'The Official Student Community Publication of CSPC I August - December 2021',
    date: 'January 9, 2022'
  },
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER I Volume XL I No. 2',
    desc: 'The Official Student Community Publication of CSPC I January - May 2021',
    date: 'June 15, 2021'
  },
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER I Volume XL I No. 1',
    desc: 'The Official Student Community Publication of CSPC I August - December 2020',
    date: 'December 28, 2020'
  },
  {
    image: '/images/blind_idolatry.jpg',
    title: 'NEWSLETTER I Volume XXXIX I No. 2',
    desc: 'The Official Student Community Publication of CSPC I January - May 2020',
    date: 'June 10, 2020'
  }
];

const handleExplore = (issue) => {
  alert(`Opening ${issue.title}`);
};
</script>
