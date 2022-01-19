<template>
  <div>
    <h2>Koliki je fond sati.</h2>
    <div class="grid grid-cols-1 md:grid-cols-2">
      <SelectWithLabel
        labelText="mjesec"
        selectID="mjeseci"
        :options="mjeseci"
        v-model="mjesec.mjesec"
      />
      <SelectWithLabel
        labelText="godina"
        selectID="godine"
        :options="godine"
        v-model="mjesec.godina"
      />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
      <div>
        Ako se računa 5 dana po 8 sati
        <br />
        ukupni je fond sati: {{ fond().svi }}
        <br />
        blagdani: {{ fond().blagdani }}
        <br />
        ({{ fond().svi - fond().blagdani }} + {{ fond().blagdani }})
      </div>
      <div>
        Ako se računa 5 dana po 7 sati i subota 5 sati
        <br />
        ukupni je fond sati: {{ fond().svi575 }}
        <br />
        blagdani: {{ fond().blagdani575 }}
        <br />
        ({{ fond().svi575 - fond().blagdani575 }} + {{ fond().blagdani575 }})
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['blagdani'],
  data() {
    return {
      mjesec: {
        mjesec: 1,
        godina: 2022,
      },
      mjeseci: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
      godine: [2020, 2021, 2022],
    };
  },
  methods: {
    fond() {
      var fond = {
        svi: 0,
        blagdani: 0,
        svi575: 0,
        blagdani575: 0,
      };
      var daysInMonth = new Date(this.mjesec.godina, this.mjesec.mjesec, 0).getDate();
      for (var d = new Date(this.mjesec.godina, this.mjesec.mjesec - 1, 1); d <= new Date(this.mjesec.godina, this.mjesec.mjesec - 1, daysInMonth); d.setDate(d.getDate() + 1)) {
        var datestring = ("0" + d.getDate()).slice(-2) + "." + ("0" + (d.getMonth() + 1)).slice(-2) + "." + d.getFullYear();
        //console.log(d,datestring,this.blagdani[0].date);
        switch (d.getDay()) {
          case 0:
            //day = "Sunday";
            break;
          case 1:
          //day = "Monday";
          case 2:
          //day = "Tuesday";
          case 3:
          //day = "Wednesday";
          case 4:
          //day = "Thursday";
          case 5:
            //day = "Friday";
            fond.svi = fond.svi + 8;
            fond.svi575 = fond.svi575 + 7;
            if (this.blagdani.map(function (h) { return h.date }).indexOf(datestring) != -1) {
              fond.blagdani = fond.blagdani + 8;
              fond.blagdani575 = fond.blagdani575 + 7;
            }
            break;
          case 6:
            //day = "Saturday";
            fond.svi575 = fond.svi575 + 5;
            if (this.blagdani.map(function (h) { return h.date }).indexOf(datestring) != -1) {
              fond.blagdani575 = fond.blagdani575 + 5;
            }
        };
      }
      return fond;
    }
  },
};
</script>
