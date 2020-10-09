package hr.zdraveksprite.javalotto.lib;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Lottery {
    private String name;
    private DrawType lottoType;
    private DrawType bonusType;
    private Map<Date, Draw[]> draws;
    private Map<Date, int[][]> analizes;
    private int[][] lastAnalize;
    
    public Lottery (String name, DrawType lotto, DrawType bonus) {
        this.name = name;
        this.lottoType = lotto;
        this.bonusType = bonus;
        this.draws = new HashMap<>();
        this.analizes = new HashMap<>();
        this.lastAnalize = new int[2][];
        this.lastAnalize[0] = new int[lotto.getY()];
        for (int i = 0; i < lotto.getY(); i++)
            this.lastAnalize[0][i] = -1;
        this.lastAnalize[1] = new int[bonus.getY()];
        for (int i = 0; i < bonus.getY(); i++)
            this.lastAnalize[1][i] = -1;
    }
    
    public void add (Date date, Draw lotto, Draw bonus) {
        boolean isOK = true;
        if (!lotto.isValid(lottoType)) {
            System.out.print("Lotto-");
            isOK = false;
        }
        if (!bonus.isValid(bonusType)) {
            System.out.print("Bonus-");
            isOK = false;
        }
        if (isOK) {
            this.draws.put(date, new Draw[] {lotto, bonus});
            int[] lottoA = new int[lastAnalize[0].length];
            for (int i = 0; i < lottoA.length; i++) {
                if (lotto.contains(i+1)) {
                    lottoA[i] = 0;
                } else {
                    lottoA[i] = lastAnalize[0][i] + 1;
                }
            }

            int[] bonusA = lastAnalize[1];/*
            for (int i = 0; i < bonusA.length; i++) {
                if (bonus.contains(i+1)) {
                    bonusA[i] = 0;
                } else {
                    bonusA[i]++;
                }
            }*/

            this.analizes.put(date, new int[][] {lottoA, bonusA});
            this.lastAnalize = new int[][] {lottoA, bonusA};
        } else {
            System.out.println("Error " + date + " " + lotto + " " + bonus);
        }
    }
    
    public Map<Date, Draw> getLottos () {
        Map<Date, Draw> lottos = new HashMap<>();
        draws.forEach((k, v) -> lottos.put(k, v[0]));
        return lottos;
    }

    public Map<Date, Draw> getBonuss () {
        Map<Date, Draw> bonuss = new HashMap<>();
        draws.forEach((k, v) -> bonuss.put(k, v[0]));
        return bonuss;
    }
    
    public int size() {
        return draws.size();
    }
    
    public List<Date> getDates() {
        List<Date> dates = new ArrayList<>();
        draws.forEach((k, v) -> dates.add(k));
        Collections.sort(dates);
        return dates;
    }

    public Object[] getArr(Date date) {
        Draw lotto = draws.get(date)[0];
        Draw bonus = draws.get(date)[1];
        Object[] test = new Object[2 + lotto.size() + bonus.size()];
        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");  
        test[0] = dateFormat.format(date);
        System.arraycopy(lotto.getNumbers(), 0, test, 1, lotto.size());
        System.arraycopy(bonus.getNumbers(), 0, test, 1 + lotto.size(), bonus.size());
        int[][] temp = analizes.get(date);
        test[1 + lotto.size() + bonus.size()] = Arrays.toString(temp[0]);
        return test;
    }

    public Object[] getAnalize() {
        Object[] test = new Object[1];
        List<Date> dates = this.getDates();
        


        return test;
    }
    
    @Override
    public String toString() {
        return "U bazi " + name + " lutrije je " + draws.size() + " izvlačenja.";
    }
}
