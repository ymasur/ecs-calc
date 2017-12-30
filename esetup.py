#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Module: esetup.py
Contains the initialization steps at startup

@author: ymasur@microclub.ch (YM)

00.12.2017/YM
"""
import os, time

samples_per_hour = 6

infile = "tempdata.txt"
outfile = "tempdata.report.txt"

def help(argv):
    """ Aide en ligne sur le programme
    :rtype : object void
    """
    print(u"\nUtilisation:\n"
          u"%s <infile> <outfile> \n"
          u"\nAvec:\n"
          u"infile : chemin/fichier à lire\n"
          u"outfile : chemin/fichier à traiter\n"
          u"Par défaut, <outfile> est <infile> renommé '.calc.txt'"
          % argv[0]
    )
    return

def set_args(argv):
    # type: (object) -> object
    """ lit les arguments en ligne de commande
        1: Le fichier des données
        2: Le fichier de sortie, à créer
    """
    global infile, outfile

    if len(argv) == 1:
        infile = time.strftime("%y%m") + "data.txt"
        outfile = infile.replace(".txt", ".calc.txt")
        return

    if len(argv) > 1 and ("/?" in argv[1]):
        help(argv)
        exit(0)

    if len(argv) > 1 and os.path.isfile(argv[1]):
        infile = argv[1]
    else:
        print("Pas de fichier %s!" % argv[1])
        exit(-1)

    if len(argv) > 2:
        outfile = argv[2]
    else:
        outfile = argv[1].replace(".txt", ".calc.txt")

#end of setargs