package fsobot;

import javax.swing.UnsupportedLookAndFeelException;

public class Launcher {
    public static void main(String[] args) {
        setLookAndFeel();
        Display window = new Display();
    }
    
    static void setLookAndFeel() {
        for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    try { javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    } catch (ClassNotFoundException e1) {} catch (InstantiationException e2) {} catch (IllegalAccessException ex) {} catch (UnsupportedLookAndFeelException ex) {}
                    break;
                }
            }
    }
}