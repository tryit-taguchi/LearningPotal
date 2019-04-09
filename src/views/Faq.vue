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
      ]
    }
  }
}
</script>

<style lang="scss">
  
</style>