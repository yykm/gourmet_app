<template>
  <div id="photoList">
    <b-container fluid>
      <b-row>
        <b-col><PhotoForm :shopId="shopId"/></b-col>
      </b-row>
      <b-row align-h="start">
        <b-col sm="6" md="4" lg="3" class="flex-grow-0 my-2" v-for="photoId in photoIds" :key="photoId"
          ><Photo :photoId="photoId"
        /></b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Photo from "./../components/Photo.vue";
import PhotoForm from "./../components/PhotoForm.vue";

export default {
  name: "PhotoList",
  components: {
    Photo,
    PhotoForm
  },
  data() {
    return {
      shopId: this.$route.params.id, // 店舗ID
      photoIds: ["a", "b", "c", "d","e","f","g",] // 写真ID
    };
  }
};
</script>

<style scoped>
</style>
