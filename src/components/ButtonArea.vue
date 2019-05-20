<template>
  <div class="button-area"><slot></slot></div>
</template>

<script>
export default {
  data(){
    return{
    }
  }
}
</script>

<style lang="scss">
.button-area{
  display: flex;
  align-items: center;
  justify-content: flex-end;
  margin-top: 20px;
}
</style>
