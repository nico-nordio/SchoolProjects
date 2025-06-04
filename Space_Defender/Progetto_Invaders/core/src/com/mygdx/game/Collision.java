package com.mygdx.game;

public class Collision {
    private float x;
    private float y;
    private final int width;
    private final int height;

    /**
     * Costruttore che setta gli attributi del rettangolo con quelli che saranno dell'oggetto (bullet, enemyship, ecc...)
     * @param x
     * @param y
     * @param width
     * @param height
     */
    Collision(float x, float y, int width, int height) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
    }

    public void move(float x, float y) {
        this.x = x;
        this.y = y;
    }

    /**
     * Metodo che verifica se il rettangolo della collisione collide con un altro rettangolo
     *
     * @param rect
     * @return boolean
     */
    public boolean collidesWith(Collision rect) {
        return x < rect.x + rect.width && y < rect.y + rect.height && x + width > rect.x && y + height > rect.height;
    }

}
