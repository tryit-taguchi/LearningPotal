<template>
  <div>
    <page-title>
      キャッチフレーズ 結果（みんなの声にも流用）
    </page-title>
    <div style="margin-right:100px;margin-left:100px;">
      <p style="font-size:3rem;">投票有難うございます！本日のベスト３は…</p>
      <p style="font-size:3rem;margin:10px auto;">
        <img src="../assets/icon_crown_gold.png" class="img-in-text" alt="">&ensp;Best1&ensp;なんちゃって！
      </p>
      <p style="font-size:3rem;margin:10px auto;">
        <img src="../assets/icon_crown_silver.png" class="img-in-text" alt="">&ensp;Best2&ensp;qqq
      </p>
      <p style="font-size:3rem;margin:10px auto;">
        <img src="../assets/icon_crown_bronze.png" class="img-in-text" alt="">&ensp;Best2&ensp;aaaaa
      </p>

      <p class="catchword-sofar-heading">これまでのキャッチフレーズ</p>

      <p style="font-size:3rem;margin:20px auto 10px;">首都圏エリア（GRANDRIVE）</p>
      <ul>
        <li style="font-size:3rem;margin-left:50px;">
          ハッピーdays
        </li>
        <li style="font-size:3rem;margin-left:50px;">
          日産 本気の軽
        </li>
      </ul>
      <ul>
        <li style="font-size:3rem;margin-left:50px;">
          エブリデイズ
        </li>
        <li style="font-size:3rem;margin-left:50px;">
          軽を超えた軽
        </li>
      </ul>
      <p style="font-size:3rem;margin:20px auto 10px;">北海道エリア（十勝スピードウェイ）</p>
      <ul>
        <li style="font-size:3rem;margin-left:50px;">
          軽もやっちゃいました！普通車みたいな軽、それが日産デイズ
        </li>
        <li style="font-size:3rem;margin-left:50px;">
          もう、軽だからって我慢しない！
        </li>
      </ul>
      <ul>
        <li style="font-size:3rem;margin-left:50px;">
          登録車のＤＮＡと、日産の先進技術を手に入れた、最強の軽。
        </li>
        <li style="font-size:3rem;margin-left:50px;">
          おッ軽！デイズだよ！
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped lang="scss">

.catchword-sofar-heading{
  font-size:3rem;
  text-align:center;
  display: flex;
  white-space: nowrap;
  align-items: center;
  &%ba{
    content: "";
    display: block;
    height: 2px;
    background-color: #fff;
    flex: 1 1 100%;
  }
  &::before{
    @extend %ba;
    margin-right: 2rem;
  }
  &::after{
    @extend %ba;
    margin-left: 2rem;
  }
}
</style>