<template>
              sa
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

</template>
    
<script>
export default {
   
}
</script>
    
<style>
     
</style>
