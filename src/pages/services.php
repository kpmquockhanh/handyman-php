<?php
require '../db.php';
require_once __DIR__ . '/../helpers.php';
// Fetch all services
$stmt = $conn->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero services">
    <div class="container">
        <div>
            <h1>Handyman Services You Can Trust</h1>
            <p class="clamped-text">
                From minor repairs to major improvements, our skilled handymen are here to make your life easier. Whether
                it’s fixing a leaky faucet, assembling furniture, or painting a room, we handle it all with care and
                professionalism.
            </p>
            <div class="buttons">
                <button class="btn btn-primary">
                    <span class="text">Request a quote</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <button class="btn btn-secondary">
                    <i class="fa-solid fa-phone"></i>
                    <span class="text"> Call +61 402 195 476</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="statistics">
    <div class="container">
        <div class="stats">
            <div class="stat-item">
                <i class="fa-solid fa-user"></i>
                <div class="stat-content">
                    <h4>100,000+ Five-Star</h4>
                    <p>Customer Reviews</p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fa-solid fa-briefcase"></i>
                <div class="stat-content">
                    <h4>1,000s of jobs</h4>
                    <p>completed each year</p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fa-solid fa-graduation-cap"></i>
                <div class="stat-content">
                    <h4>Highly trained</h4>
                    <p>Local Franchisees</p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fa-solid fa-lock"></i>
                <div class="stat-content">
                    <h4>Police checked</h4>
                    <p>& Fully Insured</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="waves blue"></div>

<section class="services">
    <div class="container">
        <h2>HANDYMAN SERVICES</h2>
        <p>Need help around the house? Our comprehensive handyman services cover everything from electrical repairs and
            carpentry to plumbing and painting. No job is too small or too complex for our experienced team.</p>

        <div class="cards">
            <?php foreach ($services as $service) : ?>
            <div class="card" style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.6)),
    url(<?= get_image_cdn($service['image']) ?>) no-repeat center center/cover;">
                <div class="content title"><?= $service['name'] ?></div>
                <div class="content desc"><?= $service['description'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="video">
    <div class="container flex justify-content-center mb">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/xmJfPxpp2f0?si=fGSFF5VCP-Imtwkm"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <h2 class="white">What Our Clients Say</h2>
        <div class="testimonial-quote">
            <blockquote>
                <img class="stars" src="/assets/images/stars.png" alt="rating">
                <p>John was fantastic! He arrived on time, fixed my leaky faucet, and even tightened a loose door
                    hinge
                    without me asking. His professionalism and attention to detail were outstanding. Highly
                    recommend him for
                    any home repair needs!</p>
                <footer>- Emily Rogers, Sydney</footer>
            </blockquote>
            <blockquote>
                <img class="stars" src="/assets/images/stars.png" alt="rating">
                <p>I called Mark to install some shelves and repair a broken drawer. He was incredibly efficient,
                    friendly,
                    and left everything spotless after he finished. The shelves are perfectly level, and the drawer
                    works like
                    new. I'll definitely be using his services again.</p>
                <footer>- Paul Stevenson, Parramatta</footer>
            </blockquote>

            <blockquote>
                <img class="stars" src="/assets/images/stars.png" alt="rating">
                <p>Sarah went above and beyond when helping us repaint our living room and fix some damaged skirting
                    boards.
                    The work was flawless, and she even helped us pick a better shade of paint! Affordable and
                    reliable—she’s our go-to handyman now.</p>
                <footer>- Linda Walker, Bondi Beach</footer>
            </blockquote>
        </div>
    </div>
</section>

<section class="motto">
    <div class="container flex gap-4">
        <img class="rounded-xs" src="/assets/images/image6.jpg" alt="Trustworthy Handyman Services in Sydney">
        <div>
            <h4>Trustworthy Handyman Services in Sydney – No Job Too Big or Small for Our Skilled Team</h4>
            <p class="mt-xs">Looking for reliable handyman services in Sydney? Our team has been providing top-notch
                assistance to
                Sydneysiders for years, tackling tasks big and small with ease. Whether you need help with a complex
                renovation project or a simple repair, we’ve got you covered. Contact us today for a free quote or
                give us a
                call at 131-546.</p>
        </div>

    </div>
</section>
<div class="waves black"></div>