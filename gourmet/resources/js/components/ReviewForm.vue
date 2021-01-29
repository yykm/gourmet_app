<template>
    <div class="reviewForm">
        <b-form @submit.prevent="addComment" @reset.prevent="onReset">
            <!-- 写真データ -->
            <b-form-file v-model="photo"></b-form-file>

            <!-- コメント -->
            <b-form-textarea v-model="comment" rows="3" max-rows="6"></b-form-textarea>
            <b-button type="submit" variant="primary">Submit</b-button>
            <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
    </div>
</template>

<script>
import { mapMutations } from "vuex";
import { ERR } from "./../store/const.js";

export default {
    name: "ReviewForm",
    data() {
        return {
            photo: null,
            comment: null,
            erros: null
        };
    },
    props: {
        shopId: {
            type: String,
            required: true
        }
    },
    methods: {
        ...mapMutations("Err", ["setCode"]),
        ...mapMutations("Message", ["setContent"]),

        // コメント投稿
        async addComment() {
            // FormDataオブジェクトに写真ファイルと店舗ID追加
            const formData = new FormData();

            // FormDataオブジェクトに写真ファイルと店舗ID追加
            // formData.append("content", this.comment);
            formData.append("shop_id", this.shopId);

            if (this.photo) {
                // 写真があれば写真投稿APIを呼び出す
                formData.append("photo", this.photo);

                const response = await axios
                    .post(`/api/photos`, formData)
                    .catch(err => err.response || err);

                // バリデーションエラー
                if (response.status === ERR.UNPROCESSABLE_ENTITY) {
                    // エラーメッセージを設定
                    this.errors = response.data.errors;
                    return;
                } else if (response.status !== ERR.CREATED) {
                    // エラーコード設定
                    this.setCode(response.status);

                    // 失敗メッセージ表示
                    this.setContent({
                        success: false,
                        content: "コメントの投稿に失敗しました",
                        timeout: 3000
                    });
                    return;
                }

                // photoフィールドを削除
                formData.delete("photo");
                // photo_idフィールドに投稿した写真のidを追加
                formData.append("photo_id", response.data.id);
            } else {
                // 写真が指定されていない場合はnull
                // formData.append("photo_id", null);
            }

            // コメント内容がない場合
            if (!this.comment) {
                return;
            }
console.log(...formData.entries());

            // コメント内容を格納
            formData.append("content", this.comment);
            const response = await axios
                .post(`/api/comments`, formData)
                .catch(err => err.response || err);
            // バリデーションエラー
            if (response.status === ERR.UNPROCESSABLE_ENTITY) {
                // エラーメッセージを設定
                this.errors = response.data.errors;
                return;
            } else if (response.status !== ERR.CREATED) {
                // エラーコード設定
                this.setCode(response.status);

                // 失敗メッセージ表示
                this.setContent({
                    success: false,
                    content: "コメントの投稿に失敗しました",
                    timeout: 3000
                });
                return;
            }

            console.log("成功!", response.data);
        },

        // 入力値リセット
        onReset() {
            this.photo = null;
            this.comment = null;
        }
    }
};
</script>

<style scoped>

</style>
