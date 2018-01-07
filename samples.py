#!/usr/bin/python
#  -*- coding: utf-8 -*-
"""
Module: samples.py
Load data samples from text file

@author: ymasur@microclub.ch (YM)

31.12.2017/YM
"""
from __future__ import unicode_literals

__module__ = "samples"
__author__ = 'Yves Masur'


class Samples:
    """ Classe des échantillons """
    instanceCounter = 0
    s_array = []  # global var where the samples are stored

    def __init__(self, dd=0, mm=0, pump=0, t_pannel=0.0, t_es=0.0, t_ecs=0.0):
        self.dd = dd
        self.mm = mm
        self.pump = pump
        self.t_pannel = t_pannel
        self.t_es = t_es
        self.t_ecs = t_ecs
        self.t_medium = 0.0
        Samples.instanceCounter += 1

    def __repr__(self):
        return 'day=%d; min=%d; pump=%d; panel=%.2f; ES=%.2f; ECS=%.2f' % \
               (self.dd, self.mm, self.pump, self.t_pannel, self.t_es, self.t_ecs)

    def addSample(self, line):
        global s_array

        elem = line.split("\t")  # ex. ['17/10/01', '02:20:00', '0', '19.3', '41.1', '49.1']
        if elem.__len__() < 5:
            return "ERR: nb of elements:%d, expected 5" % elem.__len__()

        try:
            self.dd = int(elem[0][6:8])  # jour du mois
            self.mm = int(elem[1][0:2]) * 60 + int(elem[1][3:5])  # minutes du jour
            self.pump = int(elem[2])
            self.t_pannel = float(elem[3])
            self.t_es = float(elem[4])
            self.t_ecs = float(elem[5])
            self.t_medium = self.t_es / 2.0 + self.t_ecs / 2.0
            # self.t_medium = self.t_ecs
        except ValueError:
            return "ERR:%s" % line
        Samples.s_array.append(self)
        return 1  # 1 element added

# end class Samples


def readFile(fileName="tempdata.txt"):
    """
    :param fileName: path/name of data file
    :return:
    """
    try:
        fp = open(fileName, "r")
        for line in fp:
            s = Samples()
            rc = s.addSample(line)
            if rc == 1:
                Samples.instanceCounter += 1
            else:
                print(rc)
        fp.close()

    except IOError:
        print("Fichier: %s pas accessible" % fileName)
        exit(2)

# end readFile


# Unity tests here
if __name__ == '__main__':
    line1 = "17/10/01	01:10:00	48	44.9	36.9	45.4\n"
    line31 = "17/10/31	10:00:00	48	44.9	36.9	45.4\n"
    s = Samples()
    s.addSample(line1)
    print(s.__repr__())  # day=1; min=70; pump=48; t panel=44.90; t ES=36.90; t ECS=45.40
    s.addSample(line31)
    print(s.__repr__())  # day=31; min=600; pump=48; t panel=44.90; t ES=36.90; t ECS=45.40
    print("array: %d" % s_array.__len__())

    readFile()  # datas of tempdata.txt
    print("array: %d" % s_array.__len__())
