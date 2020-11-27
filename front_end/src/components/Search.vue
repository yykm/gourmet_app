<template>
  <div class="search">
    <b-container>
      <div class="wrapper py-4">
        <b-form @submit="onSubmit" @reset="onReset" v-if="show">
          <b-row cols="2" cols-md="4" align-v="center" align-h="around">
            <!-- *** -->

            <b-col>
              <b-form-group
                id="input-group-1"
                label="大エリア:"
                label-for="input-1"
              >
                <b-form-select
                  id="input-1"
                  v-model="form.food"
                  :options="foods"
                  required
                ></b-form-select>
              </b-form-group>
            </b-col>

            <b-col>
              <b-form-group
                id="input-group-1"
                label="中エリア:"
                label-for="input-1"
              >
                <b-form-select
                  id="input-1"
                  v-model="form.food"
                  :options="foods"
                  required
                ></b-form-select>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-1"
                label="ジャンル:"
                label-for="input-1"
              >
                <b-form-select
                  id="input-1"
                  v-model="form.food"
                  :options="foods"
                  required
                ></b-form-select>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group
                id="input-group-1"
                label="並び順:"
                label-for="input-1"
              >
                <b-form-select
                  id="input-1"
                  v-model="form.food"
                  :options="foods"
                  required
                ></b-form-select>
              </b-form-group>
            </b-col>

            <!-- *** -->
          </b-row>

          <b-form-row cols="2">
            <b-col>
              <div class="text-right">
                <b-button
                  type="submit"
                  variant="primary"
                  class="buttons mr-2 mt-3 px-4"
                  >検索</b-button
                >
              </div>
            </b-col>
            <b-col>
              <div class="text-left">
                <b-button
                  type="reset"
                  variant="danger"
                  class="buttons ml-1 mt-3 px-4"
                  >リセット</b-button
                >
              </div>
            </b-col>
          </b-form-row>
        </b-form>
      </div>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        email: '',
        name: '',
        food: null,
        checked: []
      },
      foods: [
        { text: '選択..', value: null },
        'Carrots',
        'Beans',
        'Tomatoes',
        'Corn'
      ],
      show: true
    };
  },
  methods: {
    onSubmit(evt) {
      evt.preventDefault();
      alert(JSON.stringify(this.form));
    },
    onReset(evt) {
      evt.preventDefault();
      // Reset our form values
      this.form.email = '';
      this.form.name = '';
      this.form.food = null;
      this.form.checked = [];
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  }
};
</script>

<style scoped>
.search {
  box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.2);
}
.buttons {
  width: 120px;
}
</style>
