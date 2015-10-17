# Guild Wars 2 API - Character - PHP
- Uses ParallelCurl by petewarden ( https://github.com/petewarden/ParallelCurl )
- Example of the wrapper used for a plugin: http://chippalrus.ca/Cennette/

# Description
This is just the *backend* of the project for a plugin that I have built ( http://chippalrus.ca/Cennette/ ) . This wrapper has a lack of error checking. This is bad. ༼ ◕д◕ ༽ I'm a terrible programmer.

༼ ◕_◕ ༽ I am aware of what is currently avaliable, but, I do not want to include methods I wont be using from other wrappers. 
 
Other wrappers provide a better framework, however, I disliked what was available at the time, so, I made my own. ˶⚈Ɛ⚈˵ My focus was to share my character's Builds/Equipment via a single URL without need of registering account and to display information in a compact manner without needless information.
 
=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
## (ﾉﾟ▽ﾟ)ﾉ︵ Caching
- Requested information is cached automatically with no duplication.

## (ﾉﾟ▽ﾟ)ﾉ︵ API Request calls
- API requests will check for cached file before making calls.
- Requests can be single or in batches.

## (ﾉﾟ▽ﾟ)ﾉ︵ Character Data
- Grabs Character Equipment and Specialization setup.
- Calculates *Equipment / Upgrades / Infusions* into attributes. ( Traits aren't calculated )
- This also fixes some of its "description" attributes and places them into "attributes".
