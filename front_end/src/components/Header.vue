<template>
  <b-navbar
    type="white"
    variant="white"
    class="shadow-sm justify-content-center"
  >
    <b-navbar-nav>
      <b-nav-item to="/">{{ site_name }}</b-nav-item>
    </b-navbar-nav>
  </b-navbar>
</template>

<script>
export default {
  name: 'Header',
  props: {
    site_name: {
      type: String,
      default: 'site_name'
    }
  }
};
</script>

<style scoped>
a {
  color: black;
  font-size: 1.3rem;
}
</style>
