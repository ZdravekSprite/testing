<template>
  <div class="container" title="radni sati">
    <div
      :class="[
        day.night_duration.split(':')[0]*1 > 0 ? 'd' + day.night_duration.split(':')[0] : '',
        'hours',
      ]"
    ></div>
    <div
      :class="[
        day.start.split(':')[0]*1 > 0 ? 'h' + day.start.split(':')[0] : '',
        day.duration.split(':')[0]*1 > 0 ? 'd' + day.duration.split(':')[0] : '',
        'hours',
      ]"
    ></div>
  </div>
</template>

<script>
export default {
  props: ["day"],
};
</script>

<style scoped>
/* Make sure that padding behaves as expected */
* {
  box-sizing: border-box;
}
/* Container for skill bars */
.container {
  width: 100%; /* Full width */
  min-height: 18px;
  background-color: #BBBBBB;
  position: relative;
}
.hours {
  min-height: 18px;
  position: absolute;
  color: white; /* White text color */
  background-color: #4caf50; /* Green background */
}
.d00 {
  width: 0px;
}
.d01 {
  width: 4.17%;
}
.d02 {
  width: 8.33%;
}
.d03 {
  width: 12.5%;
}
.d04 {
  width: 16.67%;
}
.d05 {
  width: 20.83%;
}
.d06 {
  width: 25%;
}
.d07 {
  width: 29.17%;
}
.d08 {
  width: 33.33%;
}
.d09 {
  width: 37.5%;
}
.d10 {
  width: 41.67%;
}
.d11 {
  width: 45.83%;
}
.d12 {
  width: 50%;
}
.d13 {
  width: 54.17%;
}
.d14 {
  width: 58.33%;
}
.d15 {
  width: 62.5%;
}
.d16 {
  width: 66.67%;
}

.h01 {
  margin-left: 4.17%;
}
.h02 {
  margin-left: 8.33%;
}
.h03 {
  margin-left: 12.5%;
}
.h04 {
  margin-left: 16.67%;
}
.h05 {
  margin-left: 20.83%;
}
.h06 {
  margin-left: 25%;
}
.h07 {
  margin-left: 29.17%;
}
.h08 {
  margin-left: 33.33%;
}
.h09 {
  margin-left: 37.5%;
}
.h10 {
  margin-left: 41.67%;
}
.h11 {
  margin-left: 45.83%;
}
.h12 {
  margin-left: 50%;
}
.h13 {
  margin-left: 54.17%;
}
.h14 {
  margin-left: 58.33%;
}
.h15 {
  margin-left: 62.5%;
}
.h16 {
  margin-left: 66.67%;
}
.h17 {
  margin-left: 70.83%;
}
.h18 {
  margin-left: 75%;
}
.h19 {
  margin-left: 79.17%;
}
.h20 {
  margin-left: 83.33%;
}
.h21 {
  margin-left: 87.5%;
}
.h22 {
  margin-left: 91.67%;
}
.h23 {
  margin-left: 95.83%;
}
</style>