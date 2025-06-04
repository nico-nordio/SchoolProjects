package com.mygdx.game;

import com.badlogic.gdx.Gdx;
import com.badlogic.gdx.Input;
import com.badlogic.gdx.Screen;
import com.badlogic.gdx.graphics.Color;
import com.badlogic.gdx.graphics.Texture;
import com.badlogic.gdx.graphics.g2d.BitmapFont;
import com.badlogic.gdx.graphics.g2d.GlyphLayout;
import com.badlogic.gdx.utils.ScreenUtils;
import java.util.ArrayList;
import java.util.Random;

public class GameScreen implements Screen {

    private static final float SPEED = 350;

    private static final int SHIP_WIDTH = 90;
    private static final int SHIP_HEIGHT = 100;

    private static final float SHOOT_WAIT_TIME = 0.3f;
    private static final float MIN_ENEMY_SPAWN_TIME = 0.4f;
    private static final float MAX_ENEMY_SPAWN_TIME = 0.8f;

    private final Texture playerShip;

    private float x;
    private final float y;

    private float shootTimer;
    private float enemySpawnTimer;

    private final Random random;

    private final SpaceGame game;

    private final ArrayList<Bullet> bullets;

    private final ArrayList<EnemyShip> enemyShips;

    private final ArrayList<Explosion> explosions;

    private final Texture blank;

    private final BitmapFont scoreFont;

    private final Collision playerRect;

    private float health = 1; //0 = dead | 1 = full health

    private int score;

    GameScreen(SpaceGame game) {
        this.game = game;
        y = 15; //floor level
        x = (float) (SpaceGame.getWidth() / 2 - SHIP_WIDTH / 2);

        bullets = new ArrayList<>();

        enemyShips = new ArrayList<>();

        explosions = new ArrayList<>();

        scoreFont = new BitmapFont(Gdx.files.internal("fonts/score.fnt"));

        playerRect = new Collision(0, 0, SHIP_WIDTH, SHIP_HEIGHT);

        blank = new Texture("hp.png");

        score = 0;

        random = new Random();
        enemySpawnTimer = random.nextFloat() * (MAX_ENEMY_SPAWN_TIME - MIN_ENEMY_SPAWN_TIME) + MIN_ENEMY_SPAWN_TIME;

        shootTimer = 0;

        playerShip = new Texture("battleships/FIGHTER_01__REBEL.png");
    }

    @Override
    public void show() {

    }

    @Override
    public void render(float delta) {

        //Codice per lo sparo e allineamento bullets con la playerShip
        shootTimer += delta;
        if (Gdx.input.isKeyPressed(Input.Keys.SPACE) && shootTimer >= SHOOT_WAIT_TIME) {
            shootTimer = 0;

            int offsetSx = 7;
            int offsetDx = 12;

            bullets.add(new Bullet(x + offsetSx));
            bullets.add(new Bullet(x + SHIP_WIDTH - offsetDx));
        }

        //enemyShip spawn time
        enemySpawnTimer -= delta;
        if (enemySpawnTimer <= 0) {
            enemySpawnTimer = random.nextFloat() * (MAX_ENEMY_SPAWN_TIME - MIN_ENEMY_SPAWN_TIME) + MIN_ENEMY_SPAWN_TIME;
            enemyShips.add(new EnemyShip(random.nextInt(SpaceGame.getWidth() - EnemyShip.getWIDTH())));
        }

        //Update enemyShips
        ArrayList<EnemyShip> enemyShipToRemove = new ArrayList<>();
        for (EnemyShip enemyShip : enemyShips) {
            enemyShip.update(delta);
            if (enemyShip.isRemove()) {
                enemyShipToRemove.add(enemyShip);
            }
        }

        //Update bullets
        ArrayList<Bullet> bulletsToRemove = new ArrayList<>();
        for (Bullet bullet : bullets) {
            bullet.update(delta);
            if (bullet.isRemove()) {
                bulletsToRemove.add(bullet);
            }
        }

        //Update explosion
        ArrayList<Explosion> explosionToRemove = new ArrayList<>();
        for (Explosion explosion : explosions) {
            explosion.update(delta);
            if (explosion.isRemove()) {
                explosionToRemove.add(explosion);
            }
        }
        explosions.removeAll(explosionToRemove);

        //Codice per il movimento
        if (Gdx.input.isKeyPressed(Input.Keys.LEFT) || Gdx.input.isKeyPressed(Input.Keys.A)) {
            x -= SPEED * Gdx.graphics.getDeltaTime();

            if (x < 0) {
                x = 0;
            }
        }
        if (Gdx.input.isKeyPressed(Input.Keys.RIGHT) || Gdx.input.isKeyPressed(Input.Keys.D)) {
            x += SPEED * Gdx.graphics.getDeltaTime();

            if (x + SHIP_WIDTH > SpaceGame.getWidth()) {
                x = SpaceGame.getWidth() - SHIP_WIDTH;
            }
        }
        playerRect.move(x, y);

        //Codice per controllare le collisioni tra proiettile sparato dal player e la nave nemica
        for (Bullet bullet : bullets) {
            for (EnemyShip enemyShip : enemyShips) {
                if (bullet.getCollision().collidesWith(enemyShip.getCollision())) {
                    bulletsToRemove.add(bullet);
                    enemyShipToRemove.add(enemyShip);
                    explosions.add(new Explosion(enemyShip.getX(), enemyShip.getY()));
                    score += 100;
                }
            }
        }
        enemyShips.removeAll(enemyShipToRemove);
        bullets.removeAll(bulletsToRemove);

        //Codice per controllare le collisioni tra il player e la nave nemica
        for (EnemyShip enemyShip : enemyShips) {
            if (enemyShip.getCollision().collidesWith(playerRect)) {
                enemyShipToRemove.add(enemyShip);
                health -= 0.05f;

                if (health <= 0f) {
                    this.dispose();
                    game.setScreen(new GameOverScreen(game, score));
                    return;
                }
            }
        }

        ScreenUtils.clear(0, 0, 0, 1);

        game.batch.begin();

        game.getBackground().updateAndRender(delta, game.batch);

        GlyphLayout scoreLayout = new GlyphLayout(scoreFont, "" + score);
        scoreFont.draw(game.batch, scoreLayout, (float) SpaceGame.getWidth() / 2 - scoreLayout.width / 2, SpaceGame.getHeight() - scoreLayout.height - 10);

        for (Bullet bullet : bullets) {
            bullet.render(game.batch);
        }

        for (EnemyShip enemyShip : enemyShips) {
            enemyShip.render(game.batch);
        }

        for (Explosion explosion : explosions) {
            explosion.render(game.batch);
        }

        if (health > 0.6f) {
            game.batch.setColor(Color.GREEN);
        } else if (health > 0.2f) {
            game.batch.setColor(Color.ORANGE);
        } else {
            game.batch.setColor(Color.RED);
        }
        game.batch.draw(blank, 0, 0, SpaceGame.getWidth() * health, 5);

        game.batch.setColor(Color.WHITE);

        game.batch.draw(playerShip, x, y, SHIP_WIDTH, SHIP_HEIGHT);

        game.batch.end();
    }

    @Override
    public void resize(int width, int height) {

    }

    @Override
    public void pause() {

    }

    @Override
    public void resume() {

    }

    @Override
    public void hide() {

    }

    @Override
    public void dispose() {

    }
}
