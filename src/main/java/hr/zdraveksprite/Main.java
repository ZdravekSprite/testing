package hr.zdraveksprite;

import java.awt.Color;
import java.awt.Font;
import java.awt.Frame;
import java.awt.Label;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import javax.swing.JDialog;

public class Main extends JDialog {

    private static final long serialVersionUID = 1L;

    public Main() {
        //Create a frame
        Frame f = new Frame();
        f.setSize(300, 200);
        f.setTitle("Test v" + serialVersionUID);

        //Prepare font
        Font font = new Font("SansSerif", Font.PLAIN, 22);

        //Write something
        Label label = new Label("Test Label");
        label.setForeground(Color.BLUE);
        label.setFont(font);
        f.add(label);

        //Make visible
        f.setVisible(true);
        f.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                System.exit(0);
            }
        });
    }

    public static void main(final String[] args) {
        Main main = new Main();
    }
}
