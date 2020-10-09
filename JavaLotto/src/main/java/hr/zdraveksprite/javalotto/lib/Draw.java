package hr.zdraveksprite.javalotto.lib;

import java.util.Arrays;
import java.util.HashSet;
import java.util.Set;

public class Draw {
    private Integer[] numbers;
    
    public Draw(Integer[] numbers) {
        this.numbers = numbers;
    }
    
    public boolean contains(int value) {
        for (int number : numbers) {
            if (value == number) return true;
        }
        return false;
    }
    
    public Integer[] getNumbers() {
        return numbers;
    }
    
    public int size() {
        return numbers.length;
    }

    private boolean areDistinct() { 
        Set<Integer> s = new HashSet<>(Arrays.asList(numbers)); 
        return (s.size() == numbers.length); 
    }

    private boolean validSize(DrawType type) { 
        return (type.getX() == numbers.length); 
    }

    private boolean validRange(DrawType type) {
        for (int i = 0; i < this.size(); i++) {
            if (numbers[i] <= 0 || numbers[i] > type.getY()) {
                return false;
            }
        }
        return true;
    }
    
    public boolean isValid(DrawType type) {
        if (!this.areDistinct()) return false;
        if (!this.validSize(type)) return false;
        if (!this.validRange(type)) return false;
        return true;
    }
    @Override
    public String toString() {
        return Arrays.toString(numbers);
    }
}
