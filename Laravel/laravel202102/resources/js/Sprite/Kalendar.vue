<template>
  <div>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
      <ul id="kalendar">
        <li
          v-for="day in allDaysInMonth"
          :key="day.day"
          :style="{
            color: day.holy ? 'red' : 'black',
            backgroundColor:
              day.def > 5 ? 'white' : day.def < 5 ? 'lightblue' : 'cyan',
          }"
        >
          {{ day.day }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("kalendar mounted");
  },
  props: ["year", "month", "holidays"],
  methods: {
    makeDay: function (d, m, y) {
      var sick = false;
      var hours = 0;
      const holy = this.holidays.some(
        (day) => day.date === d + "." + m + "." + y
      )
        ? true
        : false;
      const work = JSON.parse(this.$page.props.user.work_days);
      //console.log(work);
      if (work.some((day) => day.d === d + "." + m + "." + y)) {
        const work_day = work.find((day) => day.d === d + "." + m + "." + y);
        if (work_day.s) {
          sick = work_day.s;
        } else {
          hours = work_day.h.reduce((sum, h) => sum + h.d.split(":")[0] * 1, 0);
        }
      }
      const dayIndex = new Date(m + "/" + d + "/" + y).getDay();
      const day = {
        day: d + "." + m + "." + y,
        holy: holy,
        sick: sick,
        def: dayIndex === 0 ? 0 : dayIndex < 6 ? 7 : 5,
        hours: hours,
      };
      return day;
    },
    makeAllDaysInMonth: function (m, y) {
      var daysInMonth = [];
      for (var i = 1; i <= new Date(y, m, 0).getDate(); i++) {
        daysInMonth.push(this.makeDay(i, m, y));
      }
      return daysInMonth;
    },
  },
  computed: {
    allDaysInMonth() {
      console.log(this.makeAllDaysInMonth(this.month, this.year));
      return this.makeAllDaysInMonth(this.month, this.year);
    },
  },
};
</script>