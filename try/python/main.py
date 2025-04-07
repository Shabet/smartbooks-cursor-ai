import pygame
import random
import sys

# Initialize Pygame
pygame.init()

# Screen dimensions
SCREEN_WIDTH = 800
SCREEN_HEIGHT = 600
screen = pygame.display.set_mode((SCREEN_WIDTH, SCREEN_HEIGHT))
pygame.display.set_caption("Breakout")

# Colors
BLACK = (0, 0, 0)
WHITE = (255, 255, 255)
RED = (255, 0, 0)
BLUE = (0, 0, 255)
GREEN = (0, 255, 0)
YELLOW = (255, 255, 0)
ORANGE = (255, 165, 0)

# Paddle class
class Paddle(pygame.sprite.Sprite):
    def __init__(self):
        super().__init__()
        self.image = pygame.Surface((100, 20))
        self.image.fill(WHITE)
        self.rect = self.image.get_rect()
        self.rect.centerx = SCREEN_WIDTH // 2
        self.rect.bottom = SCREEN_HEIGHT - 10
        self.speed = 8

    def update(self):
        keys = pygame.key.get_pressed()
        if keys[pygame.K_LEFT] and self.rect.left > 0:
            self.rect.x -= self.speed
        if keys[pygame.K_RIGHT] and self.rect.right < SCREEN_WIDTH:
            self.rect.x += self.speed

# Ball class
class Ball(pygame.sprite.Sprite):
    def __init__(self, paddle):
        super().__init__()
        self.image = pygame.Surface((10, 10))
        self.image.fill(WHITE)
        self.rect = self.image.get_rect()
        self.rect.centerx = paddle.rect.centerx
        self.rect.bottom = paddle.rect.top
        self.speedx = random.choice([-4, 4])
        self.speedy = -4
        self.paddle = paddle
        self.launched = False

    def update(self):
        if not self.launched:
            self.rect.centerx = self.paddle.rect.centerx
            self.rect.bottom = self.paddle.rect.top
        else:
            self.rect.x += self.speedx
            self.rect.y += self.speedy

            # Bounce off walls
            if self.rect.left <= 0 or self.rect.right >= SCREEN_WIDTH:
                self.speedx = -self.speedx
            if self.rect.top <= 0:
                self.speedy = -self.speedy

            # Bounce off paddle
            if self.rect.colliderect(self.paddle.rect):
                self.speedy = -abs(self.speedy)
                # Change direction based on where the ball hits the paddle
                relative_intersect_x = (self.paddle.rect.centerx - self.rect.centerx) / (self.paddle.rect.width / 2)
                self.speedx = -relative_intersect_x * 5

# Brick class
class Brick(pygame.sprite.Sprite):
    def __init__(self, x, y, color):
        super().__init__()
        self.image = pygame.Surface((80, 30))
        self.image.fill(color)
        self.rect = self.image.get_rect()
        self.rect.x = x
        self.rect.y = y

# Create sprite groups
all_sprites = pygame.sprite.Group()
bricks = pygame.sprite.Group()

# Create paddle
paddle = Paddle()
all_sprites.add(paddle)

# Create ball
ball = Ball(paddle)
all_sprites.add(ball)

# Create bricks
colors = [RED, ORANGE, YELLOW, GREEN, BLUE]
for row in range(5):
    for col in range(10):
        brick = Brick(col * 80, row * 30 + 50, colors[row])
        all_sprites.add(brick)
        bricks.add(brick)

# Game loop
clock = pygame.time.Clock()
running = True
lives = 3

while running:
    # Keep loop running at the right speed
    clock.tick(60)
    
    # Process input/events
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            running = False
        elif event.type == pygame.KEYDOWN:
            if event.key == pygame.K_SPACE and not ball.launched:
                ball.launched = True

    # Update
    all_sprites.update()

    # Check for ball-brick collisions
    brick_hits = pygame.sprite.spritecollide(ball, bricks, True)
    if brick_hits:
        ball.speedy = -ball.speedy

    # Check if ball is out of bounds
    if ball.rect.top > SCREEN_HEIGHT:
        lives -= 1
        if lives <= 0:
            running = False
        else:
            ball = Ball(paddle)
            all_sprites.add(ball)

    # Check if all bricks are destroyed
    if len(bricks) == 0:
        running = False

    # Draw / render
    screen.fill(BLACK)
    all_sprites.draw(screen)
    
    # Draw lives
    font = pygame.font.Font(None, 36)
    lives_text = font.render(f'Lives: {lives}', True, WHITE)
    screen.blit(lives_text, (10, 10))
    
    # Flip the display
    pygame.display.flip()

pygame.quit()
sys.exit()
