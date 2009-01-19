#!/usr/bin/env python

from musicbrainz2.webservice import Query, ArtistFilter
import csv, time

dataReader = csv.reader(open('sxsw.csv'), delimiter='\t', quotechar='"')
dataWriter = csv.writer(open('sxsw-mb.csv', 'w'), delimiter='\t', quotechar='"', quoting=csv.QUOTE_MINIMAL)

for row in dataReader:
  f = ArtistFilter(name=row[0], limit=1)
  q = Query()
  results = q.getArtists(f)
  time.sleep(1)

  if results:
    artist = results[0].artist
    if artist.name == row[0]:
      row.append(artist.id)
      print row
    
      dataWriter.writerow(row)

