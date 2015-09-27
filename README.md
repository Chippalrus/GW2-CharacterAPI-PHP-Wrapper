# Guild Wars 2 API - Character - PHP
- Uses ParallelCurl by petewarden ( https://github.com/petewarden/ParallelCurl )

# Description
I don't code in PHP, therefore, this is likely managed poorly and inconsistant.

I disliked GW2 web-apps that were available at the time, so, I made my own.
My focus was to share my character's Builds/Equipment via a single URL without need of registering account and to display information in a compact manner without needless information.

This is just the *backend* of the project that I have built. I am aware of what is currently avaliable, however, I do not want to include methods I wont be using from other wrappers.

# Features
## Caching
- Requested information is cached automatically with no duplication.

## API Request calls
- API requests will check for cached file before making calls.
- Requests can be single or in batches.

## Character Data
- Grabs Character Equipment and Specialization setup.
- Calculates *Equipment / Upgrades / Infusions* into attributes.
