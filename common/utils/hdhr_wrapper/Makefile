SRCFILE=src/hdhr_wrapper.c
OUTFILE=hdhr_wrapper

#####
# QNAP Specific build instructions
#
QNAP_CT_TOOLS=/home/rik/workspace/qnap-xcc/CT
QNAP_ARM_GCC=$(QNAP_CT_TOOLS)/arm-Realtek-linux-androideabi/bin/arm-linux-androideabi-gcc
QNAP_X86_GCC=$(QNAP_CT_TOOLS)/i686-QNAP-linux-gnu/cross-tools/bin/i686-QNAP-linux-gnu-gcc
QNAP_X86_64_GCC=$(QNAP_CT_TOOLS)/x86_64-QNAP-linux-gnu/cross-tools/bin/x86_64-QNAP-linux-gnu-gcc

QNAP_ARM_OUTDIR=qnap/arm
QNAP_X86_OUTDIR=qnap/x86
QNAP_X86_64_OUTDIR=qnap/x86_64

# Not working at this time - removed from QNAP_ALL
$(QNAP_ARM_OUTDIR)/$(OUTFILE):
	$(QNAP_ARM_GCC) -o $(QNAP_ARM_OUTDIR)/$(OUTFILE) $(SRCFILE)
	
$(QNAP_X86_OUTDIR)/$(OUTFILE):
	$(QNAP_X86_GCC) -o $(QNAP_X86_OUTDIR)/$(OUTFILE) $(SRCFILE)

$(QNAP_X86_64_OUTDIR)/$(OUTFILE):
	$(QNAP_X86_64_GCC) -o $(QNAP_X86_64_OUTDIR)/$(OUTFILE) $(SRCFILE)

qnap_all: $(QNAP_X86_OUTDIR)/$(OUTFILE) $(QNAP_X86_64_OUTDIR)/$(OUTFILE)


#####
# Synology Specific build instructions
#


	
####
# General Build instructions
#
all: qnap_all
	
clean:
	rm $(QNAP_X86_OUTDIR)/$(OUTFILE) $(QNAP_X86_64_OUTDIR)/$(OUTFILE)
