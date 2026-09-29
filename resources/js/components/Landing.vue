<template>
  <div class="landing">
    <header class="landing-nav">
      <div class="auth-logo"><span class="brand-mark">S</span><span>Schedule</span></div>
      <nav>
        <a href="#features">Features</a>
        <a href="#how">How it works</a>
        <router-link class="button button-primary" to="/login">Sign in</router-link>
      </nav>
    </header>

    <section class="hero">
      <p class="eyebrow">Your publishing workspace</p>
      <h1>Plan once. Publish everywhere.</h1>
      <p class="hero-sub">
        Draft posts, choose your platforms, and schedule everything from one calm content calendar.
      </p>
      <div class="hero-actions">
        <router-link class="button button-primary" to="/login">Get started</router-link>
        <a class="button button-secondary" href="#features">See features</a>
      </div>
    </section>

    <section id="features" class="section">
      <h2 class="section-title">Everything you need to stay consistent</h2>
      <div class="grid">
        <article v-for="f in features" :key="f.title" class="surface card">
          <span class="stat-icon">{{ f.icon }}</span>
          <h3>{{ f.title }}</h3>
          <p class="section-caption">{{ f.text }}</p>
        </article>
      </div>
    </section>

    <section id="how" class="section">
      <h2 class="section-title">How it works</h2>
      <ol class="steps">
        <li v-for="(s, i) in steps" :key="s.title">
          <span class="step-num">{{ i + 1 }}</span>
          <div><strong>{{ s.title }}</strong><p class="section-caption">{{ s.text }}</p></div>
        </li>
      </ol>
    </section>

    <section class="cta surface">
      <h2 class="section-title">Ready to move from idea to published?</h2>
      <router-link class="button button-primary" to="/login">Sign in to Schedule</router-link>
    </section>

    <footer class="landing-footer">© {{ new Date().getFullYear() }} Schedule</footer>
  </div>
</template>

<script setup>
const features = [
  { icon: '◷', title: 'Smart scheduling', text: 'Queue posts for the exact time you want, with a daily cap to keep your feed balanced.' },
  { icon: '▤', title: 'Content calendar', text: 'See what is coming up next and spot gaps in your publishing plan.' },
  { icon: '⚙', title: 'Multiple platforms', text: 'Connect social, blog, and news destinations and target several at once.' },
  { icon: '✎', title: 'Drafts and media', text: 'Save work in progress and attach images before you publish.' },
];

const steps = [
  { title: 'Write your post', text: 'Add a title, your content, and an optional image.' },
  { title: 'Pick platforms', text: 'Choose one or more destinations for the post.' },
  { title: 'Schedule it', text: 'Set the time and let your queue do the rest.' },
];
</script>

<style scoped>
.landing { max-width: 1080px; margin: 0 auto; padding: 0 20px 40px; }
.landing-nav { display: flex; justify-content: space-between; align-items: center; padding: 20px 0; }
.landing-nav nav { display: flex; align-items: center; gap: 20px; }
.landing-nav nav a:not(.button) { text-decoration: none; color: inherit; opacity: .75; }
.hero { text-align: center; padding: 80px 0 64px; }
.hero h1 { font-size: clamp(2.2rem, 6vw, 3.8rem); line-height: 1.1; margin: 8px auto 16px; max-width: 14ch; }
.hero-sub { max-width: 52ch; margin: 0 auto 28px; opacity: .75; font-size: 1.1rem; }
.hero-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.section { padding: 40px 0; }
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 20px; }
.card { padding: 22px; }
.card h3 { margin: 12px 0 6px; }
.steps { list-style: none; padding: 0; margin: 20px 0 0; display: grid; gap: 18px; }
.steps li { display: flex; gap: 14px; align-items: flex-start; }
.steps p { margin: 4px 0 0; }
.step-num { flex: none; width: 32px; height: 32px; border-radius: 50%; display: grid; place-items: center; background: #6366f1; color: #fff; font-weight: 700; }
.cta { text-align: center; padding: 40px 20px; margin-top: 24px; display: grid; gap: 16px; justify-items: center; }
.landing-footer { text-align: center; padding-top: 32px; opacity: .55; font-size: 14px; }
html { scroll-behavior: smooth; }
</style>