package hr.zdraveksprite.javalotto;

import java.util.Arrays;

public class Draw {
    private int drawX;
    private int drawY;
    private int[] numbers;
    
    public Draw(int drawX, int drawY, int[] numbers) {
        this.drawX = drawX;
        this.drawY = drawY;
        this.numbers = numbers;
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
