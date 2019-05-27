<template>
  <div>
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
    <template v-for="(faqCat, catName) in displayFaqList">
      <h2 key="faqList.categoryName" :key="catName">{{catName}}</h2>
      <div v-for="(faq, faqId, index) in faqCat" :key="faqId">
        <dl class="faq">
          <dt class="faq-q">
            <span class="faq-q-label">質問{{index+1}}</span>
            <span class="faq-q-text">{{faq.Q_STR}}</span>
          </dt>
          <dd class="faq-a">
            <span class="faq-a-label">回答</span>
            <span class="faq-a-text">{{faq.A_STR}}</span>
          </dd>
        </dl>
        <!-- 追加質問の回答があったら表示？必要なのかわからない -->
        <dl class="faq-add" v-if="false">
          <dt class="faq-add-q">
            <span class="faq-add-q-label">追加質問</span>
            <span class="faq-add-q-text">追加質問あああああ</span>
          </dt>
          <dd class="faq-add-a">
            <span class="faq-add-a-label">回答</span>
            <span class="faq-add-a-text">追加質問の回答いいいいい</span>
          </dd>
        </dl>
        <div class="faq-add-form">
          <label for="">追加質問</label>
          <textarea class="autoheight" name="Q_STR" id="Q_STR_<?= $prec['FAQ_ID'] ?>" cols="30" rows="1"></textarea>
          <input type="button" value="送信" onClick="answerSendAdd('form_<?= $prec['FAQ_ID'] ?>')">
        </div>

      </div>
    </template>
    <hr>
    <h2>新しい質問をする</h2>
    <div class="faq-new-form-cat">
      <label for="">対象カテゴリ</label>
      <div class="faq-new-form-cat-select">
        <select name="FAQ_CAT" id="faqCat">
          <option value="">選択して下さい</option>
          <option v-for="cat in catList" value="cat">{{cat}}</option>
        </select>
      </div>
    </div>
    <div class="faq-new-form-q">
      <label for="">追加質問</label>
      <textarea class="autoheight" name="Q_STR" id="Q_STR" cols="30" rows="1"></textarea>
      <input type="button" value="送信" id="BTN_MAIN" onClick="answerSendMain('form_main')">
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      pageType: 'faq',
      faqList: [],
      currentCat: ""
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
    },
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
  background-image: -webkit-gradient(linear, left top, left bottom, from(#666), color-stop(25%, #fff), color-stop(75%, #fff), to(#666));
  background-image: linear-gradient(#666, #fff 25%, #fff 75%, #666);
  border-radius: 5px;
}

//*****************************/
// Answers Page Layout
//*****************************/
.faq{
  &-list{
    &-layout{
      width: 100%;
      max-width: 1024px;
    }
    &-form{
      display: flex;
      align-items: flex-start;
      label{
        font-size: 2.0rem;
        border: none;
        padding: .2em;
        line-height: 1.2;
        margin-right: 5px;
        white-space: nowrap;
      }
      select{
        margin-right: 5px;
      }
      textarea{
        width: auto;
        flex: 1 0 auto;
        margin-right: 5px;

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
        align-self: flex-end;
      }
    }
  }
  &-item{
    color: #000;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #fff;
    dt{
      padding: 10px;
      border-bottom: 1px solid #000;
    }
    dd{
      margin-left: 0;
      padding: 10px;
    }
  }
  &-additional{
    margin-left: 10%;
  }
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