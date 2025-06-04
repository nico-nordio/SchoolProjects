package com.mygdx.game;

import com.badlogic.gdx.graphics.Texture;
import com.badlogic.gdx.graphics.g2d.SpriteBatch;

public class EnemyShip {
    private static final int SPEED = 300;
    private static final int HEIGHT = 100;
    private static final int WIDTH = 100;

    private static Texture texture;

    private final float x;
    private float y;

    private final Collision rect;

    private boolean remove = false;

    EnemyShip(float x){
        this.x = x;
        this.y = SpaceGame.getHeight();

        this.rect = new Collision(this.x, y, WIDTH, HEIGHT);

        if(texture == null){
            texture = new Texture("battleships/FIGHTER_01__TIE.png");
        }
    }

    public void update(float deltaTime){
        y -= SPEED * deltaTime;
        if(y < -HEIGHT){
            remove = true;
        }
        rect.move(x, y);
    }

    public void render(SpriteBatch batch){
        batch.draw(texture, x, y, WIDTH, HEIGHT);
    }

    public Collision getCollision(){
        return rect;
    }

    public float getX() {
        return x;
    }

    public float getY() {
        return y;
    }

    public static int getWIDTH() {
        return WIDTH;
    }

    public boolean isRemove() {
        return remove;
    }
}
