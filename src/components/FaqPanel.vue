<template>
  <div>
    <dl class="faq">
      <dt class="faq-q" @click="isOpened=!isOpened">
        <span class="faq-q-label">質問{{index+1}}</span>
        <span class="faq-q-text">{{data.Q_STR}}</span>
      </dt>
      <transition name="fade">
        <dd class="faq-a" v-show="isOpened">
          <span class="faq-a-label">回答</span>
          <span class="faq-a-text">{{data.A_STR}}</span>
        </dd>
      </transition>
    </dl>
    <!-- 追加質問の回答があったら表示？必要なのかわからない -->
    <transition name="fade">
      <dl class="faq-add" v-if="false" v-show="isOpened">
        <dt class="faq-add-q">
          <span class="faq-add-q-label">追加質問</span>
          <span class="faq-add-q-text">追加質問あああああ</span>
        </dt>
        <dd class="faq-add-a">
          <span class="faq-add-a-label">回答</span>
          <span class="faq-add-a-text">追加質問の回答いいいいい</span>
        </dd>
      </dl>
    </transition>
    <transition name="fade">
      <div class="faq-add-form" v-show="isOpened">
        <label for="">追加質問</label>
        <textarea class="autoheight" name="Q_STR" id="Q_STR_<?= $prec['FAQ_ID'] ?>" cols="30" rows="1"></textarea>
        <input type="button" value="送信" onClick="answerSendAdd('form_<?= $prec['FAQ_ID'] ?>')">
      </div>
    </transition>
  </div>
</template>

<script>
export default {
	props: {
		data: Object,
    faqId: {},
    index: {}
	},
	// データ定義
	data: function(){
		return {
			isOpened: false
		}
	},
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
    overflow: hidden;
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
    overflow: hidden;
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
      overflow: hidden;
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
