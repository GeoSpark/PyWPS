#!/usr/bin/env python
#-*- coding: utf-8 -*-
"""
This program is simple implementation of OGC's [http://opengeospatial.org]
Web Processing Service (OpenGIS(r) Web Processing Service - OGC 05-007r7)
version 1.0.0 from 2007-06-08

Target of this application is to bring functionality of GIS GRASS
[http://grass.osgeo.it] to the World Wide Web - it should work like
wrapper for modules of this GIS. Though GRASS was at the first place in the
focus, it is not necessary to use it's modules - you can use any program
you can script in Python or other language.

This first version was written with support of Deutsche Bundesstiftung
Umwelt, Osnabrueck, Germany on the spring 2006. SVN server was hosted by
GDF-Hannover, Hannover, Germany; today at Intevation GmbH, Germany.

Current development is supported mainly by:
Help Service - Remote Sensing s.r.o
Cernoleska 1600
256  01 - Benesov u Prahy
Czech Republic
Europe

For setting see comments in 'etc' directory and documentation.

This program is free software, distributed under the terms of GNU General
Public License as published by the Free Software Foundation version 2 of the
License.

Enjoy and happy GISing!
"""
__version__ = "3.0-fcgi"


# Author:    Jachym Cepicky
#            http://les-ejk.cz
# License:
#
# Web Processing Service implementation
# Copyright (C) 2006 Jachym Cepicky
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

import sys
import traceback
from flup.server.fcgi import WSGIServer
from webob import Request, Response

import pywps
from pywps.Exceptions import WPSException, NoApplicableCode

def app(environ, start_response):
    try:
        request = Request(environ)
        response = Response()

        if len(request.body) == 0:
            try:
                request.body = sys.argv[1]
            except:
                response.status = 400 # Bad Request
                response.headerlist = [('Content-Type', 'application/xml; charset=UTF-8'),]
                response.body = str(NoApplicableCode('No query string found.'))
                return response(environ, start_response)
    
        wps = pywps.Pywps(request.method)

        if wps.parseRequest(request.body):
#            pywps.debug(wps.inputs)
            response_msg = wps.performRequest()
            # request performed, write the response back
            if response_msg:
                response.status = 200 # OK
                response.headerlist = [('Content-Type', wps.request.contentType),]
                response.body = response_msg
#                pywps.response.response(wps.response,
#                    sys.stdout, wps.parser.soapVersion, wps.parser.isSoap,
#                    wps.parser.isSoapExecute, wps.request.contentType)
                    
    except WPSException as e:
        traceback.print_exc(file=pywps.logFile)
        response.status = 400 # Bad Request
        response.headerlist = [('Content-Type', 'application/xml; charset=UTF-8'),]
        response.body = str(e)
#        pywps.response.response(e, sys.stdout, wps.parser.soapVersion,
#                                wps.parser.isSoap,
#                                wps.parser.isSoapExecute)
    except Exception as e:
        traceback.print_exc(file=pywps.logFile)
        response.status = 500 # Internal Server Error
        response.headerlist = [('Content-Type', 'application/xml; charset=UTF-8'),]
        response.body = str(NoApplicableCode(e.message))
    
    return response(environ, start_response)

if __name__ == '__main__':
    WSGIServer(app).run()