<template>
  <div>
    <page-title>
      <template v-slot:left>勉強会Q&A</template>
      カテゴリーごとに見る
      &ensp;
      <select name="" id="" onchange="changeCategory(this.value);">
        <option value="">選択して下さい</option>
        <option v-for="cat in categories" value="cat">{{cat}}</option>
      </select>
    </page-title>
    <p style="padding-left:40px;font-size:11pt">質問をタップで回答を表示します。</p>
    <h2>よくある質問と答え</h2>
    <template v-for="faqCat in faqData">
      <h2 key="faqList.categoryName">{{faqCat.categoryName}}</h2>
      <div v-for="(faq, index) in faqCat.faqList" key="faqId">
        <dl class="faq">
          <dt class="faq-q">
            <span class="faq-q-label">質問{{index+1}}</span>
            <span class="faq-q-text">{{faq.q}}</span>
          </dt>
          <dd class="faq-a">
            <span class="faq-a-label">回答</span>
            <span class="faq-a-text">{{faq.a}}</span>
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
          <option v-for="cat in categories" value="cat">{{cat}}</option>
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
      categories: [
        'ｴﾝｼﾞﾝ・ﾄﾗﾝｽﾐｯｼｮﾝ',
        '先進安全装備',
        '寒冷地',
        'その他',
        'その他装備',
        'バッテリー',
        'AJ',
        'D-OPT'
      ],
      faqData: [
        {
          categoryName: "ｴﾝｼﾞﾝ・ﾄﾗﾝｽﾐｯｼｮﾝ",
          faqList: [
            {
              faqId: 787,
              q: "S-HYBRIDはセレナと同じですか？",
              a: "新型デイズのS-HYBRIDは電池をリチウムイオンバッテリーに変更することで、アシスト時間が１０倍以上に進化しています。"
            },
            {
              faqId: 788,
              q: "ハイブリッド車の保証は？",
              a: "メインのハイブリッドシステム構成部品については（鉛酸バッテリーを除く）、特別保証部品とし5年/10万キロの保証が適用となります。その他の部品については、当社ガソリン車と同等の保証が適用となります（一般保証部品：3年/6万キロ、特別保証部品：5年/10万キロ）。"
            }
          ]
        },
        {
          categoryName: "先進安全装備",
          faqList: [
            {
              faqId: 789,
              q: "プロパイロットはリーフやセレナと同じですか？",
              a: "リーフ・セレナ・エクストレイルに搭載済みのプロパイロットと同様のシステムで、使い方も同じです。"
            },
            {
              faqId: 804,
              q: "SOSコールが使えるようになるために、必要なことを教えてください。",
              a: "利用条件：①ナビ取付パッケージ(ﾒｰｶｰｵﾌﾟｼｮﾝ)+9インチナビ(ﾃﾞｨｰﾗｰｵﾌﾟｼｮﾝ) + ②NissanConnectサービスの登録 + ③SOSコールサービスの申込み"
            },
            {
              faqId: 812,
              q: "SOSコールはエアバッグが展開しない事故でも自動で通報されるのでしょうか？",
              a: "Gの大きさにより、エアバッグが展開しなくても自動で通報されます。"
            },
            {
              faqId: 813,
              q: "SOSコールは運転手の返答が無い場合も自動的に通報してくれるのでしょうか？",
              a: "はい。自動で通報されます。"
            },
            {
              faqId: 814,
              q: "SOSコールが繋がらない所はあるのでしょうか？",
              a: "ドコモの通信を使っているのでドコモの電波がある場所なら繋がります。"
            }
          ]
        },
        {
          categoryName: "寒冷地",
          faqList: [
          ]
        },
        {
          categoryName: "その他",
          faqList: [
            {
              faqId: 799,
              q: "三菱の水島工場製ということは、OEMなのでしょうか？",
              a: "新型デイズの開発は日産がしました。生産は三菱の水島工場に委託しています。"
            },
          ]
        },
        {
          categoryName: "その他装備",
          faqList: [
          ]
        },
        {
          categoryName: "バッテリー",
          faqList: [
          ]
        },
        {
          categoryName: "AJ",
          faqList: [
            {
              faqId: 809,
              q: "LV車の設定はありますか？",
              a: "設定はありません。"
            },
            {
              faqId: 810,
              q: "ライダーやAUTECHの設定はありますか？",
              a: "設定はありません。"
            }
          ]
        },
        {
          categoryName: "D-OPT",
          faqList: [
            {
              faqId: 815,
              q: "ナビゲーションの選択肢は何種類ですか？",
              a: "9インチナビ(MM318D-L)と7インチナビ(MJ118D-W)の2機種設定しております。"
            },
          ]
        },
      ]
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
  }
}
</script>

<style lang="scss">
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