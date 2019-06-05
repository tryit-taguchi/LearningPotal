<template>
  <div v-if="faqViewFlg">
    <page-title>
      <template v-slot:left>勉強会Q&A</template>
      カテゴリーごとに見る
      &ensp;
      <select v-model="currentCat">
        <option value="">選択して下さい</option>
        <option v-for="cat in catList" :value="cat">{{cat}}</option>
      </select>
    </page-title>
    <p style="padding-left:40px;font-size:11pt">質問をタップで回答を表示します。</p>
    <h2>よくある質問と答え</h2>
    <transition name="fade" mode="out-in">
      <div :key="currentCat">
        <template v-for="(faqCat, catName) in displayFaqList">
          <h2>{{catName}}</h2>
          <faq-panel v-for="(faq, faqId, index) in faqCat" :faqRec="faq" :faq-id="faqId" :index="index" :currentCat="currentCat"  />
        </template>
      </div>
    </transition>
    <hr>
    <h2>新しい質問をする</h2>
    <div class="faq-new-form-cat">
      <label for="">対象カテゴリ</label>
      <div class="faq-new-form-cat-select">
        <select name="FAQ_CAT" id="faqCat" v-model="selectCat">
          <option value="">選択して下さい</option>
          <option v-for="cat in catList" :value="cat">{{cat}}</option>
        </select>
      </div>
    </div>
    <div class="faq-new-form-q">
      <label for="">追加質問</label>
      <textarea class="autoheight" name="Q_STR" v-model="addStr" id="Q_STR" cols="30" rows="1"></textarea>
      <input type="button" value="送信" id="BTN_MAIN" @click="faqInputCategory">
    </div>
    <v-dialog/>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      pageType: 'faq',
      faqList: [],
      currentCat: "",
      selectCat: "",
      addStr: "",
      faqViewFlg: false, // データセット後に描画を行う
    }
  },
  mounted() {
    const autoheight = document.querySelectorAll('.autoheight');
    for(const el of autoheight){
      el.addEventListener('keydown', () => {
        setTimeout(() => {
          el.style.cssText = 'height:auto;';
          el.style.cssText = 'height:' + el.scrollHeight + 'px';
        }, 0);
      });
    }
  },
  computed: {
    catList: function(){
      return Object.entries(this.faqList).map((v)=>v[0])
    },
    displayFaqList: function(){
      if(this.currentCat===""){
        return this.faqList
      }else{
        return {[this.currentCat]:this.faqList[this.currentCat]}
      }
    },
  },
	watch: {
		currentCat: function () {
			this.selectCat = this.currentCat;
		}
	},
  // 初回処理（createdではDOM操作をしない）
  created: function () {
    console.log('-- '+this.pageType+'_q');
    // セッション情報の取得等
    this.isLogin();
    this.startSession(this.callback_getSession);
  },
  // メソッド群
  methods: {
    // -- サーバサイドからのコールバック
    // セッション読み込み後
    callback_getSession: function() {
      // セッションを読み込み終わって状態を取得したら問題データを読み込む
      this.getJson(this.getAPIPath()+'/'+this.pageType ,this.collback_getData);
    },
    // 問題データ取得後
    collback_getData: function(response) {
      this.faqList = response.data.faqList;
      this.faqViewFlg = true;
    },

		// FAQへの追加質問を送信する（カテゴリ）
		faqInputCategory: function() {

			if( this.isEmpty(this.selectCat) ) {
				this.$modal.show('dialog', {
					text: 'カテゴリを選択してください。',
					buttons: [
						{title: 'OK'}
					]
				});
				return;
			}

			if( !this.isEmpty(this.addStr) ) {
				var form = {};
				form.addStr = this.addStr;
				form.selectCat  = this.selectCat;
				//console.log(form.addStr);
				//console.log(form.selectCat);
				this.submit(this.getAPIPath()+'/faq_i_category/' + this.getMemberId(),form, function(response) {
					this.result = response.data;
					if( this.result != null ) {
						this.$modal.show('dialog', {
						  text: '質問を送信いたしました。<br>ありがとうございました。',
						  buttons: [
						    {title: 'OK'}
						  ]
						})
					} else {
						this.$modal.show('dialog', {
							text: '通信が正常に完了しませんでした。電波の良いところで再度お試し下さい。',
							buttons: [
								{title: 'OK'}
							]
						});
					}
				}.bind(this));
			} else {
				this.$modal.show('dialog', {
					text: '質問を入力してください。',
					buttons: [
						{title: 'OK'}
					]
				});
			}
		}


  }
}
</script>

<style scoped lang="scss">
select {
  font-size: 2.0rem;
  border: none;
  padding: .2em;
  line-height: 1.2;
  color: #000;
  background-image: linear-gradient(#666, #fff 25%, #fff 75%, #666);
  border-radius: 5px;
}

.faq{
  margin: 0 0 0 50px;
  font-size: 3.0rem;
  &-q{
    display: flex;
    flex-direction: column;
    vertical-align: middle;
    margin: 0 0 5px;
    border-radius: 10px 10px 0 0;
    overflow: hidden;
    cursor: pointer;
    &-label{
      // flex: 0 1 calc(1024px - 720px);
      background-color: rgba(36,36,36,1);
      margin-left: auto;
      color: #fff;
      padding: 5px;
      display: block;
      width: 100%;
      &::before{
        content: "？";
      }
    }
    &-text{
      // flex: 0 0 720px;
      background-color: #fff;
      color: #000;
      padding: 5px;
      display: block;
      width: 100%;
    }
  }
  &-a{
    display: flex;
    vertical-align: middle;
    margin: 0 0 5px;
    &-label{
      flex: 0 1 calc(1024px - 720px);
      background-color: rgba(98,0,23,1);
      margin-left: auto;
      color: #fff;
      padding: 5px;
      &::before{
        content: "！";
      }
    }
    &-text{
      flex: 0 0 720px;
      background-color: #fff;
      color: #000;
      padding: 5px;
    }
  }
  &-add{
    margin: 0 0 0 100px;
    font-size: 3.0rem;
    &-q{
      display: flex;
      vertical-align: middle;
      margin: 0 0 5px;
      &-label{
        flex: 0 1 calc(1024px - 720px);
        background-color: rgba(36,36,36,1);
        margin-left: auto;
        color: #fff;
        padding: 5px;
        &::before{
          content: "？";
        }
      }
      &-text{
        flex: 0 0 calc(720px - 50px);
        background-color: #fff;
        color: #000;
        padding: 5px;
      }
    }
    &-a{
      display: flex;
      vertical-align: middle;
      margin: 0 0 5px;
      &-label{
        flex: 0 1 calc(1024px - 720px);
        background-color: rgba(98,0,23,1);
        margin-left: auto;
        color: #fff;
        padding: 5px;
        &::before{
          content: "！";
        }
      }
      &-text{
        flex: 0 0 calc(720px - 50px);
        background-color: #fff;
        color: #000;
        padding: 5px;
      }
    }
    &-form{
      margin: 0 0 30px 0;
      display: flex;
      align-items: flex-start;
      label{
        font-size: 3.0rem;
        border: none;
        padding: .2em;
        line-height: 1.2;
        margin-right: 5px;
        white-space: nowrap;
        flex: 1 0 auto;
        text-align: right;
      }
      textarea{
        font-size: 3.0rem;
        width: auto;
        flex: 0 0 calc(720px - 105px);

        border: none;
        padding: .2em;
        line-height: 1.2;
        font-family: "Nissan Brand font", "新ゴ";
        color: #000;
        background-color: #fff;
        border-radius: 5px;
      }
      input[type="button"],
      input[type="submit"],
      button{
        font-size: 3.0rem;
        flex: 0 0 100px;
        align-self: flex-end;
        margin-left: 5px;
      }
    }
  }
  &-new-form{
    &-cat{
      margin: 0 0 5px 0;
      display: flex;
      align-items: flex-start;
      label{
        font-size: 3.0rem;
        border: none;
        padding: .2em;
        line-height: 1.2;
        margin-right: 5px;
        white-space: nowrap;
        flex: 1 1 auto;
        text-align: right;
      }
      &-select{
        flex: 0 0 auto;
        margin-right: auto;
        flex: 0 0 720px;
        select{
          font-size: 3.0rem;
        }
      }
    }
    &-q{
      margin: 0 0 30px 0;
      display: flex;
      align-items: flex-start;
      label{
        font-size: 3.0rem;
        border: none;
        padding: .2em;
        line-height: 1.2;
        margin-right: 5px;
        white-space: nowrap;
        flex: 1 0 auto;
        text-align: right;
      }
      textarea{
        font-size: 3.0rem;
        width: auto;
        flex: 0 0 calc(720px - 105px);

        border: none;
        padding: .2em;
        line-height: 1.2;
        font-family: "Nissan Brand font", "新ゴ";
        color: #000;
        background-color: #fff;
        border-radius: 5px;
      }
      input[type="button"],
      input[type="submit"],
      button{
        font-size: 3.0rem;
        flex: 0 0 100px;
        align-self: flex-end;
        margin-left: 5px;
      }
    }
  }
}

</style>