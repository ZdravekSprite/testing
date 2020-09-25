package hr.zdraveksprite.javalotto;

import java.util.Arrays;

public class Draw {
    private DrawType drawType;
    private int[] numbers;
    
    public Draw(DrawType drawType, int[] numbers) {
        this.drawType = drawType;
        if (numbers.length == drawType.getX()) {
            this.numbers = numbers;
        }
    }
    
    public boolean contains(int value) {
        for (int number : numbers) {
            if (value == number) return true;
        }
        return false;
    }
    
    @Override
    public String toString() {
        return Arrays.toString(numbers);
    }
}
