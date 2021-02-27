import TableRow from './TableRow'

const IPTable = ({
  allDaysInMonth,
  bruto,
  prijevoz,
  year,
  month,
  odbitak,
  prirez }) => {
  const hoursNorm = allDaysInMonth.reduce((sum, d) => sum + d.def, 0)
  const hoursWork = allDaysInMonth.reduce((sum, d) => sum + d.hours, 0)
  const perHour = (bruto / hoursNorm / 100).toFixed(2)
  // 1.7a Praznici. Blagdani, izbori
  const h17a = allDaysInMonth.filter(d => d.holy).reduce((sum, d) => sum + d.def, 0)
  const kn17a = h17a * perHour
  const overWork = hoursWork - hoursNorm + h17a
  // 1.7d Bolovanje do 42 dana
  const h17d = allDaysInMonth.filter(d => d.sick).reduce((sum, d) => sum + d.def, 0)
  const kn17d = h17d * perHour * 0.7588
  // 1.1 Za redoviti rad
  const h1_1 = hoursWork > hoursNorm - h17a - h17d ? hoursNorm - h17a - h17d : hoursWork
  const kn1_1 = h1_1 * perHour
  // 1.7e Dodatak za rad nedjeljom
  const h17e = allDaysInMonth.filter(d => d.def === 0).reduce((sum, d) => sum + d.hours, 0)
  const kn17e = h17e * perHour * 0.35
  // 1.7f Dodadatak za rad na praznik
  const h17f = allDaysInMonth.filter(d => d.holy).reduce((sum, d) => sum + d.hours, 0)
  const kn17f = h17f * perHour * 0.5
  // 3.1. Prijevoz
  const kn3_1 = prijevoz
  // 3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI
  const kn3 = kn3_1
  // 5. OSNOVICA ZA OBRAČUN DOPRINOSA
  const kn5 = kn1_1 + kn17a + kn17d + kn17e + kn17f
  // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
  const kn4 = kn5 + kn3
  // 6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)
  const kn61 = kn5 * 0.15
  // 6.2. za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)
  const kn62 = kn5 * 0.05
  // 7. DOHODAK
  const kn7 = kn5 - kn61 - kn62
  // 8. OSOBNI ODBITAK 1.00 / 4000.00
  const kn8 = kn7 > odbitak ? odbitak : kn7
  // 9. POREZNA OSNOVICA
  const kn9 = kn7 - kn8
  // Porez 20%
  const kn10a = kn9 * 0.2
  // Prirez 12.00 %
  const kn10b = kn10a * prirez / 100
  // 10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK
  const kn10 = kn10a + kn10b
  // 11. NETO PLAĆA
  const kn11 = kn7 - kn10
  // 12. NAKNADE UKUPNO
  const kn12 = kn3
  // 13. NETO + NAKNADE
  const kn13 = kn11 + kn3*1
  return (
    <>
      <div className="row">
        <div className="col-6 col-s-6">
          <b>OBRAČUN ISPLAĆENE PLAĆE</b>
        </div>
        <div className="col-6 col-s-6 right">
          <b>Obrazac IP1</b>
        </div>
      </div>
      <div className="row">
        <div className="col-6 col-s-6">
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
            <li>1. Ime i prezime: <b>____</b></li>
            <li>2. Adresa: ____</li>
            <li>3. Osobni identifikacijski broj: ____</li>
            <li>4. IBAN broj računa ____ kod ____</li>
            <li>5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____</li>
          </ul>
        </div>
      </div>
      <div className="row">
        <b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {year}, MJESEC {month} DANI U MJESECU OD 1 DO {allDaysInMonth.length}
      </div>
      <TableRow
        opis={'1. OPIS PLAĆE (prekovremeni:' + (overWork > 0 ? overWork : 0) + ')'}
        sati='SATI'
        iznos='IZNOS'
        bold='true'
      />
      <TableRow
        opis='1.1. Za redoviti rad:'
        sati={h1_1}
        iznos={kn1_1}
      />
      <TableRow
        opis='1.7a Praznici. Blagdani, izbori:'
        notShow={h17a === 0}
        sati={h17a}
        iznos={kn17a}
      />
      <TableRow
        opis='1.7d Bolovanje do 42 dana:'
        notShow={h17d === 0}
        sati={h17d}
        iznos={kn17d}
      />
      <TableRow
        opis='1.7e Dodatak za rad nedjeljom'
        notShow={h17e === 0}
        sati={h17e}
        iznos={kn17e}
      />
      <TableRow
        opis='1.7f Dodatak za rad na praznik'
        notShow={h17f === 0}
        sati={h17f}
        iznos={kn17f}
      />
      <TableRow
        opis='2. OSTALI OBLICI RADA TEMELJEM KOJIH OSTVARUJE PRAVO NA UVEĆANJE PLAĆE PREMA
        KOLEKTIVNOM UGOVORU, PRAVILNIKU O RADU ILI UGOVORU O RADU I NOVČANI IZNOS
        PO TOJ OSNOVI (SATI PRIPRAVNOSTI)'
      />
      <TableRow
        opis='3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI'
        iznos={kn3}
      />
      <TableRow
        opis='3.1. Prijevoz'
        iznos={kn3_1}
      />
      <TableRow
        opis='4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.'
        iznos={kn4}
      />
      <TableRow
        opis='5. OSNOVICA ZA OBRAČUN DOPRINOSA'
        iznos={kn5}
        bold='true'
      />
      <TableRow
        opis='6. VRSTE I IZNOSI DOPRINOSA ZA OBVEZNA OSIGURANJA KOJA SE OBUSTAVLJAJU IZ PLAĆE'
      />
      <TableRow
        opis='6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)'
        iznos={kn61}
      />
      <TableRow
        opis='6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)'
        iznos={kn62}
      />
      <TableRow
        opis='7. DOHODAK'
        iznos={kn7}
        bold='true'
      />
      <TableRow
        opis={'8. OSOBNI ODBITAK ' + (odbitak*1).toFixed(2)}
        iznos={kn8}
      />
      <TableRow
        opis='9. POREZNA OSNOVICA'
        iznos={kn9}
      />
      <TableRow
        opis='10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK'
        iznos={kn10}
      />
      <TableRow
        opis='20.00 %'
        iznos={kn10a}
      />
      <TableRow
        opis={'Prirez ' + prirez + '.00 %'}
        iznos={kn10b}
      />
      <TableRow
        opis='11. NETO PLAĆA'
        iznos={kn11}
        bold='true'
      />
      <TableRow
        opis='12. NAKNADE UKUPNO'
        iznos={kn12}
      />
      <TableRow
        opis='13. NETO + NAKNADE'
        iznos={kn13}
        bold='true'
      />
      <TableRow
        opis='14. OBUSTAVE UKUPNO'
      />
      <TableRow
        opis='15. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA REDOVAN RAČUN'
      />
      <TableRow
        opis='16. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA RAČUN IZ ČL.212. OVRŠNOG ZAKONA'
      />
    </>
  )
}

export default IPTable