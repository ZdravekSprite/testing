package hr.zdraveksprite.javalotto;

import java.util.ArrayList;
import java.util.List;

public class Lottery {
    private String name;
    private List<Draw> draws;
    
    public Lottery (String name) {
        this.name = name;
        this.draws = new ArrayList<>();
    }
    
    public void add (Draw draw) {
        this.draws.add(draw);
    }
    
    public int size() {
        return draws.size();
    }
    
    public int containsA(int value) {
        int count = 0;
        for (Draw draw : draws) {
            if (draw.containsA(value)) {
                count++;
            }
        }
        return count;
    }
    
    public int containsLastA(int value, int last) {
        int count = 0;
        if (draws.size() > last) {
            for (int i = draws.size() - last; i < draws.size(); i++) {
                if (draws.get(i).containsA(value)) {
                    count++;
                }
            }
        }
        return count;
    }
    
    public int containsB(int value) {
        int count = 0;
        for (Draw draw : draws) {
            if (draw.containsB(value)) {
                count++;
            }
        }
        return count;
    }
    
    @Override
    public String toString() {
        return "U bazi " + name+ " lutrije je " + draws.size() + " izvlačenja.";
    }
}
