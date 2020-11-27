<template>
  <div class="search">
    <div class="container">
      <div>
        <b-form inline>
          <div class="flex-fill py-3">
            <div
              class="d-flex flex-column flex-md-row flex-nowrap justify-content-center"
            >
              <div class="d-flex align-content-center flex-column flex-md-row">
                <label
                  class="m-2 d-flex justify-content-center"
                  for="inline-form-custom-select-pref"
                  >大エリア</label
                >
                <b-form-select
                  id="inline-form-custom-select-pref"
                  class="m-2"
                  :options="[
                    { text: '選択..', value: null },
                    'One',
                    'Two',
                    'Three'
                  ]"
                  :value="null"
                ></b-form-select>
              </div>
              <div class="d-flex align-content-center flex-column flex-md-row">
                <label
                  class="m-2 d-flex justify-content-center"
                  for="inline-form-custom-select-pref"
                  >小エリア</label
                >
                <b-form-select
                  id="inline-form-custom-select-pref"
                  class="m-2"
                  :options="[
                    { text: '選択..', value: null },
                    'One',
                    'Two',
                    'Three'
                  ]"
                  :value="null"
                ></b-form-select>
              </div>
              <div class="d-flex align-content-center flex-column flex-md-row">
                <label
                  class="m-2 d-flex justify-content-center"
                  for="inline-form-custom-select-pref"
                  >ジャンル</label
                >
                <b-form-select
                  id="inline-form-custom-select-pref"
                  class="m-2"
                  :options="[
                    { text: '選択..', value: null },
                    'Onあああああああああe',
                    'Two',
                    'Three'
                  ]"
                  :value="null"
                ></b-form-select>
              </div>
              <div class="d-flex align-content-center flex-column flex-md-row">
                <label
                  class="m-2 d-flex justify-content-center"
                  for="inline-form-custom-select-pref"
                  >並び順</label
                >
                <b-form-select
                  id="inline-form-custom-select-pref"
                  class="m-2"
                  :options="[
                    { text: '選択..', value: null },
                    'One',
                    'Two',
                    'Three'
                  ]"
                  :value="null"
                ></b-form-select>
              </div>
            </div>
            <div>
              <div class="d-flex justify-content-center align-content-center">
                <b-button variant="primary" class="m-3 mt-4 px-4"
                  >Search</b-button
                >
                <b-button variant="danger" class="m-3 mt-4 px-4"
                  >Reset</b-button
                >
              </div>
            </div>
          </div>
        </b-form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Search'
};
</script>
<style scoped>
.search {
  width: 100%;
  box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.2);
}

label {
  display: flex;
}
</style>
