package hr.zdraveksprite.javalotto;

import java.nio.file.Paths;
import java.util.Scanner;
 
public class RecordsFromAFile {
    
    public static void main(String[] args) {
        //Scanner scanner = new Scanner(System.in);
        //System.out.println("Name of the file:");
        //String file = scanner.nextLine();

        String file = "data.txt";
        try (Scanner fileReader = new Scanner(Paths.get(file))) {
            
            String frstLine = fileReader.nextLine();
            String[] lotteryData = frstLine.split(",");
            Lottery lottery = new Lottery(lotteryData[0]);

            while (fileReader.hasNextLine()) {
                String line = fileReader.nextLine();
                String[] parts = line.split(";");
                
                String day = parts[0];
                int no1 = Integer.valueOf(parts[1]);
                int no2 = Integer.valueOf(parts[2]);
                int no3 = Integer.valueOf(parts[3]);
                int no4 = Integer.valueOf(parts[4]);
                int no5 = Integer.valueOf(parts[5]);
                int noa = Integer.valueOf(parts[6]);
                int nob = Integer.valueOf(parts[7]);
                Draw draw = new Draw(day, no1, no2, no3, no4, no5, noa, nob);

                lottery.add(draw);
                
                if (lottery.size() > 50) {
                    int avrg = 0;
                    for (int i = 1; i <= Integer.valueOf(lotteryData[2]); i++) {
                        int last = lottery.containsLastA(i, 50);
                        if (draw.containsA(i)) {
                            avrg = avrg + last;
                            System.out.print("+");
                        } else {
                            System.out.print(" ");
                        }
                        System.out.printf("%2d|", last);
                    }
                    System.out.printf("%6d=", avrg);
                    System.out.println();
                }
            }
            for (int i = 1; i <= Integer.valueOf(lotteryData[2]); i++) {
                System.out.printf("%3d|", i);
            }
            System.out.println();
            
            // System.out.println(lottery);
            
            /*
            for (int i = 1; i <= Integer.valueOf(lotteryData[2]); i++) {
                System.out.print(i + " je izvučen " + lottery.containsA(i) + " puta.");
                if (i <= Integer.valueOf(lotteryData[4])) {
                    System.out.println(i + " je izvučen " + lottery.containsB(i) + " puta iz malog bubnja.");
                } else {
                    System.out.println();
                }
            }
            */
            // test
            Draw draw = new Draw("x", 11, 20, 27, 32, 46, 3, 5);

            int avrg = 0;
            for (int i = 1; i <= Integer.valueOf(lotteryData[2]); i++) {
                int last = lottery.containsLastA(i, 50);
                if (draw.containsA(i)) {
                    avrg = avrg + last;
                    System.out.print("+");
                } else {
                    System.out.print(" ");
                }
                System.out.printf("%2d|", last);
            }
            System.out.printf("%6d=", avrg);
            System.out.println();

        } catch (Exception e) {
            System.out.println("Reading the file failed.");
        }
    }
}
