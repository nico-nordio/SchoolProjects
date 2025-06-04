package com.mygdx.game;

import com.badlogic.gdx.graphics.Texture;
import com.badlogic.gdx.graphics.g2d.SpriteBatch;

public class Bullet {
    private static final int SPEED = 500;
    private static final int DEFAULT_Y = 100;
    private static final int WIDTH = 3;
    private static final int HEIGHT = 12;

    private static Texture texture;

    private final float x;
    private float y;

    private final Collision collision;

    private boolean remove = false;

    Bullet(float x) {
        this.x = x;
        this.y = DEFAULT_Y;

        this.collision = new Collision(this.x, y, WIDTH, HEIGHT);

        if (texture == null) {
            texture = new Texture("bullet.png");
        }
    }

    public void update(float deltaTime) {
        y += SPEED * deltaTime;
        if (y > SpaceGame.getHeight()) {
            remove = true;
        }
        collision.move(x, y);
    }

    public void render(SpriteBatch batch) {
        batch.draw(texture, x, y);
    }

    public Collision getCollision() {
        return collision;
    }

    public boolean isRemove() {
        return remove;
    }
}
