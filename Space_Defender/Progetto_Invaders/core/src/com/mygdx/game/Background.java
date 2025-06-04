package com.mygdx.game;

import com.badlogic.gdx.graphics.Texture;
import com.badlogic.gdx.graphics.g2d.SpriteBatch;

public class Background {

    private static final int DEFAULT_SPEED = 40;

    private final Texture img;
    private float y1, y2;

    Background() {
        img = new Texture("background.png");
        y1 = 0;
        y2 = img.getHeight();
    }

    public void updateAndRender(float delta, SpriteBatch batch) {

        y1 -= DEFAULT_SPEED * delta;
        y2 -= DEFAULT_SPEED * delta;

        //Se l'immagine ha raggiunto la parte inferiore dello schermo e non è visibile, rimetti in alto
        if (y1 + img.getHeight() <= 0){
            y1 = y2 + img.getHeight()  ;
        }

        if (y2 + img.getHeight() <= 0){
            y2 = y1 + img.getHeight();
        }

        //Render
        batch.draw(img, 0, y1, SpaceGame.getWidth(), img.getHeight() );
        batch.draw(img, 0, y2, SpaceGame.getWidth(), img.getHeight() );
    }
}
