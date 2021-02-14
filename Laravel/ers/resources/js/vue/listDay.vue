<template>
  <div class="day">
    <span :class="[day.sick ? 'sick' : '', 'dayText']">{{ day.day }}</span>
    <input type="checkbox" @change="updateSick()" v-model="day.sick" />
    <button @click="removeDay()" class="trashcan">
      <font-awesome-icon icon="trash" />
    </button>
  </div>
</template>

<script>
export default {
  props: ["day"],
  methods: {
    updateSick() {
      axios
        .put("api/day/" + this.day.day, {
          day: this.day,
        })
        .then((response) => {
          if (response.status == 200) {
            this.$emit("daychanged");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    removeDay() {
      axios
        .delete("api/day/" + this.day.day, {
          day: this.day,
        })
        .then((response) => {
          if (response.status == 200) {
            this.$emit("daychanged");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped>
.sick {
  text-decoration: line-through;
  color: #999999;
}
.dayText {
  width: 100%;
  margin-left: 20px;
}
.day {
  display: flex;
  justify-content: center;
  align-items: center;
}
.trashcan {
  background: #e6e6e6;
  border: none;
  color: #ff0000;
  outline: none;
}
</style>