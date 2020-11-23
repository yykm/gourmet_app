<template>
  <div class="search">
    <aside>
      <div>
        <b-card header="検索条件" header-tag="header">
          <b-card-text>
            <!-- <b-form @submit="onSubmit" @reset="onReset"> -->
            <b-form>
              <!-- 大エリア -->
              <b-form-group id="hoge" label="大エリア:" label-for="input-3">
                <b-form-select
                  id="input-3"
                  plain
                  v-model="selected"
                  :options="options"
                  class="mt-3"
                ></b-form-select>
              </b-form-group>

              <!-- 小エリア -->
              <b-form-group id="hoge" label="小エリア:" label-for="input-3">
                <b-form-select
                  id="input-3"
                  plain
                  v-model="selected"
                  :options="options"
                  class="mt-3"
                ></b-form-select>
              </b-form-group>

              <!-- ジャンル -->
              <b-form-group id="hoge" label="ジャンル:" label-for="input-3">
                <b-form-select
                  id="input-3"
                  plain
                  v-model="selected"
                  :options="options"
                  class="mt-3"
                ></b-form-select>
              </b-form-group>

              <!-- 予算 -->
              <b-form-group id="hoge" label="予算:" label-for="input-3">
                <b-form-select
                  id="input-3"
                  plain
                  v-model="selected"
                  :options="options"
                  class="mt-3"
                ></b-form-select>
              </b-form-group>

              <!-- 並び順 -->
              <b-form-group id="hoge" label="並び順:" label-for="input-3">
                <b-form-select
                  id="input-3"
                  plain
                  v-model="selected"
                  :options="options"
                  class="mt-3"
                ></b-form-select>
              </b-form-group>

              <div class="d-flex justify-content-around flex-fill p-3">
                <b-button
                  type="submit"
                  variant="primary"
                  class="flex-grow-1 mr-3"
                  >検索</b-button
                >
                <b-button type="reset" variant="danger" class="flex-grow-1"
                  >リセット</b-button
                >
              </div>
            </b-form>
          </b-card-text>
        </b-card>
      </div>
    </aside>
  </div>
</template>

<script>
export default {
  name: 'Search',
  data() {
    return {
      selected: null,
      options: [
        { value: null, text: '選択...' },
        { value: 'a', text: '１つめの選択値' },
        { value: 'b', text: '２つめの選択値' },
        { value: { C: 'D' }, text: '値にオブジェクトを指定した場合' }
      ]
    };
  }
};
</script>

<style scoped></style>
