<template>
  <div>
    {{title}}
    <input type="radio" :id="type+'_on'" :name="name" value="1" :checked="1===value" @change="$emit('input', Number($event.target.value))">
    <label :for="type+'_on'">ON</label>
    <input type="radio" :id="type+'_off'" :name="name" value="0" :checked="0===value" @change="$emit('input', Number($event.target.value))">
    <label :for="type+'_off'">OFF</label>
  </div>
</template>
<script>
export default {
  props: {
    title: String,
    type: String,
    name: String,
    value: Number
  }
}
</script>
