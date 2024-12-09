<x-structure>
    <x-slot name="title">
        Home
    </x-slot>
    <h1><em>Welcome to Clash Craze!</em></h1>
    <p>Dive into the world of Clash Craze, <b>the ultimate single-player, turn-based arcade fighter</b>, meticulously crafted using the Laravel framework. In this game, <b>your strategic prowess and tactical decisions dictate your success in each intense round of combat</b>. With a straightforward interface, Clash Craze allows players to effortlessly manage their characters and match setups, ensuring a seamless and engaging gameplay experience.<br>
    <br>
    <b>Join Clash Craze today</b> and embark on a thrilling journey through dynamic arenas where every move counts. Test your skills in battles that require keen wits and sharp tactics, <b>and climb your way to the top as the champion of the Clash Craze universe!</b><p>
    <div id="stats">
        <h1>Game Statistics</h1>
        <hr>
        <h2>So far:</h2>
        <div class="stats-container">
            <p class="stats-item"><strong>Number of characters created:</strong><span class="mainPageStat">{{ $characterCount }}</span></p>
            <p class="stats-item"><strong>Number of contests played:</strong><span class="mainPageStat">{{ $contestCount }}</span></p>
        </div>
    </div>
</x-structure>
