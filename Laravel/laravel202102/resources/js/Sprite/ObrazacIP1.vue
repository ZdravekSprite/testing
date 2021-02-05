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
      <div className="col-6 col-s-6">
        <ul>
          <li><b>II. PODACI O RADNIKU/RADNICI</b></li>
          <li>1. Ime i prezime: <b>{{ $page.props.user.name }}</b></li>
          <li>2. Adresa: ____</li>
          <li>3. Osobni identifikacijski broj: ____</li>
          <li>4. IBAN broj računa ____ kod ____</li>
          <li>5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____</li>
        </ul>
      </div>
    </div>
    <div className="row">
      <b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {{ year }}, MJESEC
      {{ month }} DANI U MJESECU OD 1 DO {{ allDaysInMonth.length }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    year: {
      default: 2021,
    },
    month: {
      default: 2,
    },
    allDaysInMonth: {
      type: Array,
      default: () => [],
    },
  },
};
</script>