package com.mygdx.game;

import com.badlogic.gdx.graphics.Texture;
import com.badlogic.gdx.graphics.g2d.SpriteBatch;
import com.badlogic.gdx.graphics.g2d.TextureRegion;
import com.badlogic.gdx.graphics.g2d.Animation;

public class Explosion {
    private static final float FRAME_LENGTH = 0.2f;
    private static final int OFFSET = 8;
    private static final int SIZE = 100;
    private static final int IMAGE_SIZE = 32;

    private static Animation anim;
    private final float x;
    private final float y;
    private float stateTime;

    private boolean remove = false;

    Explosion(float x, float y){
        this.x = x - OFFSET;
        this.y = y - OFFSET;
        stateTime = 0;

        if (anim == null){
            anim = new Animation(FRAME_LENGTH, TextureRegion.split(new Texture("explosion.png"), IMAGE_SIZE, IMAGE_SIZE) [0]);
        }
    }

    public void update(float deltaTime){
        stateTime += deltaTime;
        if (anim.isAnimationFinished(stateTime)){
            remove = true;
        }
    }

    public void render (SpriteBatch batch){
        batch.draw((TextureRegion) anim.getKeyFrame(stateTime), x, y, SIZE, SIZE);
    }

    public boolean isRemove() {
        return remove;
    }
}
