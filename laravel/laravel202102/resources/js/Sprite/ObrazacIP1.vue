<template>
  <div>
    <div class="row">
      <div class="col-6 col-s-6">
        <b>OBRAČUN ISPLAĆENE PLAĆE</b>
      </div>
      <div class="col-6 col-s-6 right">
        <b>Obrazac IP1</b>
      </div>
    </div>
    <div class="row">
      <div class="col-6 col-s-6">
        <ul>
          <li><b>I. PODACI O POSLODAVCU</b></li>
          <li>1. Tvrtka/ Ime i prezime: ____</li>
          <li>2. Sjedište / Adresa: ____</li>
          <li>3. Osobni identifikacijski broj: ____</li>
          <li>4. IBAN broj računa ____ kod ____</li>
        </ul>
      </div>
      <div class="col-6 col-s-6">
        <ul>
          <li><b>II. PODACI O RADNIKU/RADNICI</b></li>
          <li>
            1. Ime i prezime: <b>{{ $page.props.user.name }}</b>
          </li>
          <li>2. Adresa: ____</li>
          <li>3. Osobni identifikacijski broj: ____</li>
          <li>4. IBAN broj računa ____ kod ____</li>
          <li>5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____</li>
        </ul>
      </div>
    </div>
    <div class="row">
      <b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {{ year }}, MJESEC
      {{ month }} DANI U MJESECU OD 1 DO {{ allDaysInMonth.length }}
    </div>
    <table-row
      :opis="'1. OPIS PLAĆE'"
      :sati="'SATI'"
      :iznos="'IZNOS'"
      :bold="true"
    />
    <table-row :opis="'1.1. Za redoviti rad:'" :sati="h1_1" :iznos="kn1_1" />
    <table-row
      :opis="
        '1.4 Za prekovremeni rad (prekovremeni:' +
        (overWork > 0 ? overWork : 0) +
        '):'
      "
      :notShow="overWork < 0"
      :sati="h1_4"
      :iznos="kn1_4"
    />
    <select :value="h1_4" v-model="h1_4">
      <option
        v-for="option in optionsH1_4"
        :value="option.value"
        :key="option.value"
      >
        {{ option.label }}
      </option>
    </select>
    <table-row
      :opis="'1.7a Praznici. Blagdani, izbori:'"
      :notShow="h17a === 0"
      :sati="h17a"
      :iznos="kn17a"
    />
    <table-row
      :opis="'1.7d Bolovanje do 42 dana:'"
      :notShow="h17d === 0"
      :sati="h17d"
      :iznos="kn17d"
    />
    <table-row
      :opis="'1.7e Dodatak za rad nedjeljom'"
      :notShow="h17e === 0"
      :sati="h17e"
      :iznos="kn17e"
    />
    <table-row
      :opis="'1.7f Dodadatak za rad na praznik'"
      :notShow="h17f === 0"
      :sati="h17f"
      :iznos="kn17f"
    />
    <table-row
      :opis="'2. OSTALI OBLICI RADA TEMELJEM KOJIH OSTVARUJE PRAVO NA UVEĆANJE PLAĆE PREMA KOLEKTIVNOM UGOVORU, PRAVILNIKU O RADU ILI UGOVORU O RADU I NOVČANI IZNOS PO TOJ OSNOVI (SATI PRIPRAVNOSTI)'"
    />
  </div>
</template>

<script>
import TableRow from "@/Sprite/TableRow";
export default {
  components: {
    TableRow,
  },
  mounted() {
    console.log("obrazac mounted");
  },
  props: ["year", "month", "bruto", "holidays"],
  data: () => {
    return {
      optionsH1_4: [
        { value: 0, label: 0 },
        { value: 1, label: 1 },
        { value: 2, label: 2 },
        { value: 3, label: 3 },
        { value: 4, label: 4 },
        { value: 5, label: 5 },
        { value: 6, label: 6 },
        { value: 7, label: 7 },
        { value: 8, label: 8 },
        { value: 9, label: 9 },
        { value: 10, label: 10 },
        { value: 11, label: 11 },
        { value: 12, label: 12 },
        { value: 13, label: 13 },
        { value: 14, label: 14 },
        { value: 15, label: 15 },
        { value: 16, label: 16 },
        { value: 17, label: 17 },
        { value: 18, label: 18 },
        { value: 19, label: 19 },
        { value: 20, label: 20 },
        { value: 21, label: 21 },
        { value: 22, label: 22 },
        { value: 23, label: 23 },
        { value: 24, label: 24 },
        { value: 25, label: 25 },
        { value: 26, label: 26 },
        { value: 27, label: 27 },
        { value: 28, label: 28 },
        { value: 29, label: 29 },
        { value: 30, label: 30 },
        { value: 31, label: 31 },
        { value: 32, label: 32 },
      ],
      h1_4: 0,
    };
  },
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
      //console.log('work', work);
      if (work.some((day) => day.d === d + "." + m + "." + y)) {
        //console.log('work', work);
        const work_day = work.find((day) => day.d === d + "." + m + "." + y);
        if (work_day.s) {
          sick = work_day.s;
          //console.log('sick', sick);
        } else {
          hours = work_day.h.reduce((sum, h) => sum + h.d.split(":")[0] * 1, 0);
          //console.log('hours', hours);
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
      //console.log(this.makeAllDaysInMonth(this.month, this.year));
      return this.makeAllDaysInMonth(this.month, this.year);
    },
    hoursNorm() {
      return this.allDaysInMonth.reduce((sum, d) => sum + d.def, 0);
    },
    hoursWork() {
      return this.allDaysInMonth.reduce((sum, d) => sum + d.hours, 0);
    },
    perHour() {
      return (this.bruto / this.hoursNorm / 100).toFixed(2);
    },
    // 1.7a Praznici. Blagdani, izbori
    h17a() {
      return this.allDaysInMonth
        .filter((d) => d.holy)
        .reduce((sum, d) => sum + d.def, 0);
    },
    kn17a() {
      return this.h17a * this.perHour;
    },
    overWork() {
      return this.hoursWork - this.hoursNorm + this.h17a;
    },
    // 1.7d Bolovanje do 42 dana
    h17d() {
      return this.allDaysInMonth
        .filter((d) => d.sick)
        .reduce((sum, d) => sum + d.def, 0);
    },
    kn17d() {
      return this.h17d * this.perHour * 0.7588;
    },
    // 1.1 Za redoviti rad
    h1_1() {
      return this.hoursWork > this.hoursNorm - this.h17a - this.h17d
        ? this.hoursNorm - this.h17a - this.h17d
        : this.hoursWork;
    },
    kn1_1() {
      return this.h1_1 * this.perHour;
    },
    // 1.4 Za prekovremeni rad
    kn1_4() {
      return this.h1_4 * this.perHour * 1.5;
    },
    // 1.7e Dodatak za rad nedjeljom
    h17e() {
      return this.allDaysInMonth
        .filter((d) => d.def === 0)
        .reduce((sum, d) => sum + d.hours, 0);
    },
    kn17e() {
      return this.h17e * this.perHour * 0.35;
    },
    // 1.7f Dodadatak za rad na praznik
    h17f() {
      return this.allDaysInMonth
        .filter((d) => d.holy)
        .reduce((sum, d) => sum + d.hours, 0);
    },
    kn17f() {
      return this.h17f * this.perHour * 0.5;
    },
  },
};
</script>